<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min="2",
     *     max="30",
     *     minMessage="Le nom du produit doit faire au moins 2 caractères",
     *     maxMessage="Le nom du produit doit faire moins de 30 caractères"
     * )
     */
    private $productName;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min="10",
     *     max="150",
     *     minMessage="La description doit faire au moins 10 caractères",
     *     maxMessage="Ladescription doit faire moins de 150 caractères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual("today", message="La date de péremption ne peut être inférieur à la date du jour")
     */
    private $expiratedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min="2",
     *     max="30",
     *     minMessage="La ville doit comporter au moins 2 caractères",
     *     maxMessage="La ville ne peut faire plus de 30 caractères"
     * )
     */
    private $location;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    private $path;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="article")
     */
    private $messages;



    public function __construct()
    {
        $this->messages = new ArrayCollection();

    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * Permet d'initialiser le slug
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->description);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getExpiratedAt(): ?\DateTimeInterface
    {
        return $this->expiratedAt;
    }

    public function setExpiratedAt(\DateTimeInterface $expiratedAt): self
    {
        $this->expiratedAt = $expiratedAt;

        return $this;
    }

    /**
     * @throws Exception
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Permet de rendre disponible un produit à sa création
     *
     * @ORM\PrePersist()
     * @return void
     */
    public function setAvailableValue()
    {
        if ($this->available === null) {
            $this->available = true;
        }
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setArticle($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getArticle() === $this) {
                $message->setArticle(null);
            }
        }

        return $this;
    }


}
