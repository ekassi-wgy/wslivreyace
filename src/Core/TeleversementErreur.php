<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Téléversement refusé, pour une raison que l'éditeur peut comprendre et
 * corriger : fichier trop lourd, format non accepté, envoi interrompu.
 *
 * Classe distincte plutôt qu'une RuntimeException ordinaire parce que son
 * message est affiché tel quel. Une exception venue d'ailleurs — PDO, système
 * de fichiers — ne doit jamais sortir vers l'écran : elle porterait des
 * chemins serveur, voire des identifiants.
 */
final class TeleversementErreur extends \RuntimeException
{
}
