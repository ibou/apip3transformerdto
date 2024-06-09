<?php

namespace App\Entity\Quest;

use App\Model\Character;
use App\Repository\Quest\ClientRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends Character
{
    use IdTrait;

    /**
     * @var Collection<int, Quest>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Quest::class)]
    private Collection $quests;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): static
    {
        if (!$this->quests->contains($quest)) {
            $this->quests->add($quest);
            $quest->setClient($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        if ($this->quests->removeElement($quest)) {
            // set the owning side to null (unless already changed)
            if ($quest->getClient() === $this) {
                $quest->setClient(null);
            }
        }

        return $this;
    }
}
