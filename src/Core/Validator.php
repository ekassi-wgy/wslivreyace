<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Validation des champs soumis.
 *
 * Volontairement petit : les règles couvrent ce que le cahier des charges
 * demande réellement (champs obligatoires, longueurs, courriel, appartenance à
 * une énumération, dates, entiers). Pas de moteur générique — il faudrait
 * l'entretenir pour un back-office de neuf écrans.
 *
 * Les messages sont en français et destinés à l'éditeur : ils disent quoi
 * corriger, pas quelle règle a échoué.
 */
final class Validator
{
    /** @var array<string,mixed> */
    private array $donnees;

    /** @var array<string,string> un message par champ, le premier rencontré */
    private array $erreurs = [];

    /** @param array<string,mixed> $donnees */
    public function __construct(array $donnees)
    {
        $this->donnees = $donnees;
    }

    /** Valeur nettoyée d'un champ : chaîne, espaces de bord retirés. */
    public function valeur(string $champ, string $defaut = ''): string
    {
        $v = $this->donnees[$champ] ?? $defaut;
        return is_scalar($v) ? trim((string) $v) : $defaut;
    }

    public function requis(string $champ, string $libelle): self
    {
        if ($this->valeur($champ) === '') {
            $this->erreur($champ, "Le champ « $libelle » est obligatoire.");
        }
        return $this;
    }

    public function courriel(string $champ, string $libelle): self
    {
        $v = $this->valeur($champ);
        if ($v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $this->erreur($champ, "« $libelle » n'est pas une adresse électronique valide.");
        }
        return $this;
    }

    public function longueur(string $champ, string $libelle, int $min = 0, ?int $max = null): self
    {
        $v = $this->valeur($champ);
        if ($v === '') {
            return $this;   // le caractère obligatoire est l'affaire de requis()
        }

        // mb_strlen et non strlen : « Yacé » fait 4 caractères, pas 5 octets.
        $n = mb_strlen($v, 'UTF-8');

        if ($n < $min) {
            $this->erreur($champ, "« $libelle » doit faire au moins $min caractères.");
        } elseif ($max !== null && $n > $max) {
            $this->erreur($champ, "« $libelle » ne doit pas dépasser $max caractères.");
        }
        return $this;
    }

    /** @param array<int,string> $valeurs */
    public function parmi(string $champ, string $libelle, array $valeurs): self
    {
        $v = $this->valeur($champ);
        if ($v !== '' && !in_array($v, $valeurs, true)) {
            $this->erreur($champ, "La valeur de « $libelle » n'est pas reconnue.");
        }
        return $this;
    }

    public function entier(string $champ, string $libelle, ?int $min = null, ?int $max = null): self
    {
        $v = $this->valeur($champ);
        if ($v === '') {
            return $this;
        }
        if (filter_var($v, FILTER_VALIDATE_INT) === false) {
            $this->erreur($champ, "« $libelle » doit être un nombre entier.");
            return $this;
        }
        $n = (int) $v;
        if ($min !== null && $n < $min) {
            $this->erreur($champ, "« $libelle » ne peut pas être inférieur à $min.");
        } elseif ($max !== null && $n > $max) {
            $this->erreur($champ, "« $libelle » ne peut pas dépasser $max.");
        }
        return $this;
    }

    /** Date au format `AAAA-MM-JJ`, et réellement existante. */
    public function date(string $champ, string $libelle): self
    {
        $v = $this->valeur($champ);
        if ($v === '') {
            return $this;
        }
        $d = \DateTimeImmutable::createFromFormat('Y-m-d', $v);
        if ($d === false || $d->format('Y-m-d') !== $v) {
            $this->erreur($champ, "« $libelle » n'est pas une date valide.");
        }
        return $this;
    }

    public function estValide(): bool
    {
        return $this->erreurs === [];
    }

    /** @return array<string,string> */
    public function erreurs(): array
    {
        return $this->erreurs;
    }

    public function erreurDe(string $champ): ?string
    {
        return $this->erreurs[$champ] ?? null;
    }

    /** Ajoute une erreur venue d'ailleurs (unicité en base, par exemple). */
    public function erreur(string $champ, string $message): self
    {
        // Le premier message l'emporte : enchaîner « obligatoire » puis
        // « trop court » sur un champ vide n'aide personne.
        $this->erreurs[$champ] ??= $message;
        return $this;
    }
}
