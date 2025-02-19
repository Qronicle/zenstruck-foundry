<?php

namespace Zenstruck\Foundry\Tests\Fixtures\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'post')]
class ODMPost implements \Stringable
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'string')]
    private $title;

    #[MongoDB\Field(type: 'string')]
    private $body;

    #[MongoDB\Field(type: 'string', nullable: true)]
    private $shortDescription;

    #[MongoDB\Field(type: 'int')]
    private $viewCount = 0;

    #[MongoDB\Field(type: 'date')]
    private $createdAt;

    #[MongoDB\Field(type: 'date', nullable: true)]
    private $publishedAt;

    #[MongoDB\EmbedMany(targetDocument: ODMComment::class)]
    private $comments;

    #[MongoDB\EmbedOne(targetDocument: ODMUser::class)]
    private $user;

    public function __construct(string $title, string $body, ODMUser $user, ?string $shortDescription = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->shortDescription = $shortDescription;
        $this->createdAt = new \DateTime('now');
        $this->comments = new ArrayCollection();
        $this->user = $user;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getUser(): ODMUser
    {
        return $this->user;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function increaseViewCount(int $amount = 1): void
    {
        $this->viewCount += $amount;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function isPublished(): bool
    {
        return null !== $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $timestamp): void
    {
        $this->publishedAt = $timestamp;
    }

    /**
     * @return Collection<ODMComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(ODMComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }

        return $this;
    }

    public function removeComment(ODMComment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
        }

        return $this;
    }
}
