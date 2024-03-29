<?php

namespace App\Entity;

use ApiPlatform\Action\PlaceholderAction;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\User\ConnectGithubController;
use App\Controller\User\MeController;
use App\Repository\UserRepository;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/me',
            controller: MeController::class,
            paginationEnabled: false,
            name: 'me'
        ),
        new Post(
            uriTemplate: '/connect-github',
            inputFormats: ['multipart' => ['multipart/form-data']],
            controller: ConnectGithubController::class,
            validationContext: ['groups' => ['Register']],
            deserialize: false,
            name: 'connect-github'
        )
    ],
    normalizationContext: ['groups' => ['Challenge', 'Challenges']],
),
    Patch(
        uriTemplate: '/me/{id}',
        requirements: ['id' => '\d+'],
        normalizationContext: ['groups' => ['UpdateMe']],
        denormalizationContext: ['groups' => ['UpdateMe']],
        security: "(is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')) and object == user",
        validationContext: ['groups' => ['Update']]
    )
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['User'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(groups: ['Register', 'Update'])]
    #[Groups(['User', 'Challenge', 'Challenges', 'LikedChallenges', 'MyChallenges', 'UpdateMe', 'Comment', 'Comments', 'Solution'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(groups: ['Register', 'Update'])]
    #[Groups(['User', 'Challenge', 'Challenges', 'LikedChallenges', 'MyChallenges', 'UpdateMe', 'Comment', 'Comments', 'Solution'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 180, unique: true)]
    #[
        NotBlank(groups: ['Register', 'Update']),
        Email(groups: ['Register', 'Update'])
    ]
    #[Groups(['User', 'UpdateMe', 'Comment', 'Comments'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['User'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[NotBlank(groups: ['Register'])]
    private ?string $password = null;

    #[EqualTo(propertyPath: 'password', message: 'Passwords are not identical', groups: ['Register'])]
    private ?string $confirm_password = null;

    #[ORM\Column]
    #[Groups(['User', 'UpdateMe'])]
    private ?bool $firstConnection = true;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Challenge::class, orphanRemoval: true)]
    private Collection $challenges;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Solution::class, orphanRemoval: true)]
    private Collection $solutions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->challenges = new ArrayCollection();
        $this->solutions = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    use Active;
    use Timestamp;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirm_password;
    }

    public function setConfirmPassword(string $confirm_password): self
    {
        $this->confirm_password = $confirm_password;

        return $this;
    }

    public function isFirstConnection(): ?bool
    {
        return $this->firstConnection;
    }

    public function setFirstConnection(bool $firstConnection): self
    {
        $this->firstConnection = $firstConnection;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Challenge>
     */
    public function getChallenges(): Collection
    {
        return $this->challenges;
    }

    public function addChallenge(Challenge $challenge): self
    {
        if (!$this->challenges->contains($challenge)) {
            $this->challenges->add($challenge);
            $challenge->setAuthor($this);
        }

        return $this;
    }

    public function removeChallenge(Challenge $challenge): self
    {
        if ($this->challenges->removeElement($challenge)) {
            // set the owning side to null (unless already changed)
            if ($challenge->getAuthor() === $this) {
                $challenge->setAuthor(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, Solution>
     */
    public function getSolutions(): Collection
    {
        return $this->solutions;
    }

    public function addSolution(Solution $solution): self
    {
        if (!$this->solutions->contains($solution)) {
            $this->solutions->add($solution);
            $solution->setAuthor($this);
        }

        return $this;
    }

    public function removeSolution(Solution $solution): self
    {
        if ($this->solutions->removeElement($solution)) {
            // set the owning side to null (unless already changed)
            if ($solution->getAuthor() === $this) {
                $solution->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }
}
