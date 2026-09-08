<?php
declare(strict_types=1);

/**
 * Les trois seules fonctions globales du projet, et elles ne sont pas là par
 * commodité.
 *
 * Tout le reste passe par des classes : c'est plus sûr, c'est cherchable, et
 * l'espace de noms global d'un projet PHP est un bien commun qu'on ne salit
 * pas sans raison. La raison est ici la lisibilité du balisage. Un texte
 * d'interface apparaît plusieurs centaines de fois dans les gabarits, souvent
 * au milieu d'une balise :
 *
 *     <a class="btn" href="...">Lire la suite</a>
 *
 * Écrit `<?= App\Core\Lexique::t('commun.lire_suite') ?>`, le libellé disparaît
 * dans son propre appel et le gabarit devient illisible. Écrit `<?= t(...) ?>`,
 * il reste une page HTML qu'on relit d'un coup d'œil.
 *
 * @see App\Core\Lexique pour le catalogue et le repli entre langues
 */

use App\Core\Lexique;

if (!function_exists('t')) {
    /**
     * Un texte d'interface, échappé et prêt à écrire dans la page.
     *
     * @param array<string,string|int> $valeurs insérés à la place de `:nom`
     */
    function t(string $cle, array $valeurs = []): string
    {
        return Lexique::t($cle, $valeurs);
    }
}

if (!function_exists('t_nu')) {
    /**
     * Un texte d'interface entièrement nu, pour un contexte qui échappera plus
     * loin : `$titre` et `$description` d'un gabarit de page, que la mise en
     * page écrit elle-même dans des attributs. Jamais dans le corps d'une page.
     *
     * @param array<string,string|int> $valeurs
     */
    function t_nu(string $cle, array $valeurs = []): string
    {
        return Lexique::nu($cle, $valeurs);
    }
}

if (!function_exists('t_brut')) {
    /**
     * Un texte d'interface non échappé, pour les rares chaînes portant du
     * balisage. Les valeurs insérées restent échappées.
     *
     * @param array<string,string|int> $valeurs
     */
    function t_brut(string $cle, array $valeurs = []): string
    {
        return Lexique::brut($cle, $valeurs);
    }
}
