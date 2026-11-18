<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partie extends Model
{
    protected $fillable = ['plateau', 'joueur_courant'];

    private const LIGNES = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6],
    ];

    public function coups(): HasMany
    {
        return $this->hasMany(Coup::class);
    }

    public function cases(): array
    {
        return str_split($this->plateau);
    }

    public function placer(int $position): void
    {
        $symbole = $this->joueur_courant;

        $plateau = $this->plateau;          // copie locale
        $plateau[$position] = $symbole;
        $this->plateau = $plateau;

        $this->joueur_courant = $symbole === 'X' ? 'O' : 'X';
        $this->save();

        $this->coups()->create(['position' => $position, 'symbole' => $symbole]);
    }

    public function gagnant(): ?string
    {
        $c = $this->cases();
        foreach (self::LIGNES as [$a, $b, $d]) {
            if ($c[$a] !== '-' && $c[$a] === $c[$b] && $c[$a] === $c[$d]) {
                return $c[$a];
            }
        }
        return null;
    }

    public function estNulle(): bool
    {
        return $this->gagnant() === null && ! in_array('-', $this->cases());
    }

    public function estTerminee(): bool
    {
        return $this->gagnant() !== null || $this->estNulle();
    }

    public function annulerDernierCoup(): void
    {
        $coup = $this->coups()->latest('id')->first();
        if (! $coup) {
            return;
        }

        $plateau = $this->plateau;
        $plateau[$coup->position] = '-';
        $this->plateau = $plateau;
        $this->joueur_courant = $coup->symbole;
        $this->save();

        $coup->delete();
    }

    public function statut(): string
    {
        if ($this->gagnant() !== null) {
            return 'Gagnée par ' . $this->gagnant();
        }
        return $this->estNulle() ? 'Match nul' : 'En cours';
    }
}