<?php
declare(strict_types=1);

namespace Duodraco\ProportionalRaffle;

use Duodraco\ProportionalRaffle\Data\Candidate;

class RaffleManager
{
    protected $candidates = [];
    protected $ratio = [];
    protected $chances = [];
    protected $urn = [];

    public function __construct(array $candidates)
    {
        $this->ratio = $this->getRatio($candidates);
        $this->chances = $this->calculateTicketsPerGender($this->ratio, count($candidates));
        foreach ($candidates as &$candidate) {
            $candidate->setTickets($this->getTicketsForCandidate($candidate));
            $this->candidates[spl_object_hash($candidate)] = $candidate;
        }
        $this->urn = $this->fillUrn();
    }

    protected function getRatio(array $candidates): array
    {
        $ratio = [];
        foreach ($candidates as $candidate) {
            $ratio[$candidate->getGender()] = $ratio[$candidate->getGender()] ?? 0;
            $ratio[$candidate->getGender()]++;
        }
        return $ratio;
    }

    protected function calculateTicketsPerGender(array $ratio, int $total)
    {
        $ticketsPerGender = [];
        foreach ($ratio as $key => $value) {
            $ticketsPerGender[$key] = $total - $value;
        }
        return $ticketsPerGender;
    }

    protected function getTicketsForCandidate(Candidate $candidate): int
    {
        return $this->chances[$candidate->getGender()];
    }

    public function fillUrn()
    {
        $urn = [];
        foreach ($this->candidates as $key => $candidate) {
            $this->setCandidateTickets($candidate, $urn);
        }
        shuffle($urn);
        return $urn;
    }

    protected function setCandidateTickets(Candidate $candidate, array &$urn)
    {
        $tickets = $candidate->getTickets();
        for ($i = 0; $i < $tickets; $i++) {
            $urn[] = $candidate->getId();
        }
    }

    /**
     * @return array
     */
    public function getUrn(): array
    {
        return $this->urn;
    }

    public function getWinner(string $winner): Candidate
    {
        return $this->candidates[$winner];
    }
}