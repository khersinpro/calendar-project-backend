<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'planning', cascade: ['persist', 'remove'])]
    private ?OrganizationUser $organization_user = null;

    /**
     * @var Collection<int, PlanningDay>
     */
    #[ORM\OneToMany(targetEntity: PlanningDay::class, mappedBy: 'planning', orphanRemoval: true)]
    private Collection $planningDays;

    /**
     * @var Collection<int, CustomPlanningDay>
     */
    #[ORM\OneToMany(targetEntity: CustomPlanningDay::class, mappedBy: 'planning', orphanRemoval: true)]
    private Collection $customPlanningDays;

    /**
     * @var Collection<int, PlanningEvent>
     */
    #[ORM\OneToMany(targetEntity: PlanningEvent::class, mappedBy: 'planning', orphanRemoval: true)]
    private Collection $planningEvents;

    public function __construct()
    {
        $this->planningDays = new ArrayCollection();
        $this->customPlanningDays = new ArrayCollection();
        $this->planningEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganizationUser(): ?OrganizationUser
    {
        return $this->organization_user;
    }

    public function setOrganizationUser(OrganizationUser $organization_user): static
    {
        // set the owning side of the relation if necessary
        if ($organization_user->getPlanning() !== $this) {
            $organization_user->setPlanning($this);
        }
        
        $this->organization_user = $organization_user;

        return $this;
    }

    /**
     * @return Collection<int, PlanningDay>
     */
    public function getPlanningDays(): Collection
    {
        return $this->planningDays;
    }

    public function addPlanningDay(PlanningDay $planningDay): static
    {
        if (!$this->planningDays->contains($planningDay)) {
            $this->planningDays->add($planningDay);
            $planningDay->setPlanning($this);
        }

        return $this;
    }

    public function removePlanningDay(PlanningDay $planningDay): static
    {
        if ($this->planningDays->removeElement($planningDay)) {
            // set the owning side to null (unless already changed)
            if ($planningDay->getPlanning() === $this) {
                $planningDay->setPlanning(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomPlanningDay>
     */
    public function getCustomPlanningDays(): Collection
    {
        return $this->customPlanningDays;
    }

    public function addCustomPlanningDay(CustomPlanningDay $customPlanningDay): static
    {
        if (!$this->customPlanningDays->contains($customPlanningDay)) {
            $this->customPlanningDays->add($customPlanningDay);
            $customPlanningDay->setPlanning($this);
        }

        return $this;
    }

    public function removeCustomPlanningDay(CustomPlanningDay $customPlanningDay): static
    {
        if ($this->customPlanningDays->removeElement($customPlanningDay)) {
            // set the owning side to null (unless already changed)
            if ($customPlanningDay->getPlanning() === $this) {
                $customPlanningDay->setPlanning(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlanningEvent>
     */
    public function getPlanningEvents(): Collection
    {
        return $this->planningEvents;
    }

    public function addPlanningEvent(PlanningEvent $planningEvent): static
    {
        if (!$this->planningEvents->contains($planningEvent)) {
            $this->planningEvents->add($planningEvent);
            $planningEvent->setPlanning($this);
        }

        return $this;
    }

    public function removePlanningEvent(PlanningEvent $planningEvent): static
    {
        if ($this->planningEvents->removeElement($planningEvent)) {
            // set the owning side to null (unless already changed)
            if ($planningEvent->getPlanning() === $this) {
                $planningEvent->setPlanning(null);
            }
        }

        return $this;
    }
}
