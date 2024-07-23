<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'schedule', cascade: ['persist', 'remove'])]
    private ?OrganizationUser $organization_user = null;

    /**
     * @var Collection<int, ScheduleDay>
     */
    #[ORM\OneToMany(targetEntity: ScheduleDay::class, mappedBy: 'schedule', orphanRemoval: true)]
    private Collection $schedule_days;

    /**
     * @var Collection<int, CustomScheduleDay>
     */
    #[ORM\OneToMany(targetEntity: CustomScheduleDay::class, mappedBy: 'schedule', orphanRemoval: true)]
    private Collection $custom_schedule_days;

    /**
     * @var Collection<int, ScheduleEvent>
     */
    #[ORM\OneToMany(targetEntity: ScheduleEvent::class, mappedBy: 'schedule', orphanRemoval: true)]
    private Collection $schedule_events;

    public function __construct()
    {
        $this->schedule_days = new ArrayCollection();
        $this->custom_schedule_days = new ArrayCollection();
        $this->schedule_events = new ArrayCollection();
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
        if ($organization_user->getSchedule() !== $this) {
            $organization_user->setSchedule($this);
        }
        
        $this->organization_user = $organization_user;

        return $this;
    }

    /**
     * @return Collection<int, ScheduleDay>
     */
    public function getScheduleDays(): Collection
    {
        return $this->schedule_days;
    }

    public function addScheduleDay(ScheduleDay $schedule_day): static
    {
        if (!$this->schedule_days->contains($schedule_day)) {
            $this->schedule_days->add($schedule_day);
            $schedule_day->setSchedule($this);
        }

        return $this;
    }

    public function removeScheduleDay(ScheduleDay $schedule_day): static
    {
        if ($this->schedule_days->removeElement($schedule_day)) {
            // set the owning side to null (unless already changed)
            if ($schedule_day->getSchedule() === $this) {
                $schedule_day->setSchedule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomScheduleDay>
     */
    public function getCustomScheduleDays(): Collection
    {
        return $this->custom_schedule_days;
    }

    public function addCustomScheduleDay(CustomScheduleDay $custom_schedule_day): static
    {
        if (!$this->custom_schedule_days->contains($custom_schedule_day)) {
            $this->custom_schedule_days->add($custom_schedule_day);
            $custom_schedule_day->setSchedule($this);
        }

        return $this;
    }

    public function removeCustomScheduleDay(CustomScheduleDay $custom_schedule_day): static
    {
        if ($this->custom_schedule_days->removeElement($custom_schedule_day)) {
            // set the owning side to null (unless already changed)
            if ($custom_schedule_day->getSchedule() === $this) {
                $custom_schedule_day->setSchedule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ScheduleEvent>
     */
    public function getScheduleEvents(): Collection
    {
        return $this->schedule_events;
    }

    public function addScheduleEvent(ScheduleEvent $schedule_event): static
    {
        if (!$this->schedule_events->contains($schedule_event)) {
            $this->schedule_events->add($schedule_event);
            $schedule_event->setSchedule($this);
        }

        return $this;
    }

    public function removeScheduleEvent(ScheduleEvent $schedule_event): static
    {
        if ($this->schedule_events->removeElement($schedule_event)) {
            // set the owning side to null (unless already changed)
            if ($schedule_event->getSchedule() === $this) {
                $schedule_event->setSchedule(null);
            }
        }

        return $this;
    }
}
