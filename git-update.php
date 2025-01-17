<?php
/**
 * Script de mise à jour depuis GitHub
 * Ce script télécharge les fichiers depuis un dépôt GitHub, vérifie la version,
 * crée une sauvegarde des fichiers existants et effectue la mise à jour si nécessaire.
 * 
 * Dépôt utilisé : https://github.com/netvincennes/proappli.com
 * 
 * **Prérequis :**
 * - PHP 8 ou version ultérieure.
 * - Extension PHP ZipArchive activée pour gérer les archives ZIP.
 * - Extension PHP SQLite activée (optionnel si la base de données SQLite est utilisée).
 * 
 * Vérifiez les extensions installées avec : php -m
 * Installez les extensions manquantes :
 * - Sur Ubuntu/Debian : sudo apt install php8.0-zip php8.0-sqlite3
 * - Sur CentOS/RHEL : sudo yum install php-zip php-sqlite3
 */

// Configuration
$githubZipUrl = 'https://github.com/netvincennes/proappli.com/archive/refs/heads/main.zip'; // URL de l'archive ZIP du dépôt GitHub
$versionUrl = 'https://raw.githubusercontent.com/netvincennes/proappli.com/main/version.txt'; // URL du fichier version.txt dans le dépôt
$tempZipFile = 'repository.zip'; // Nom du fichier temporaire pour télécharger l'archive ZIP
$extractTo = __DIR__ . '/application'; // Dossier de destination pour extraire les fichiers
$localVersionFile = $extractTo . '/version.txt'; // Chemin du fichier version local

// Vérifier les prérequis
if (PHP_VERSION_ID < 80000) {
    die("Erreur : Ce script nécessite PHP 8 ou une version ultérieure.\n");
}
if (!extension_loaded('zip')) {
    die("Erreur : L'extension ZipArchive est requise pour extraire les fichiers ZIP.\n");
}
if (!extension_loaded('sqlite3')) {
    echo "Avertissement : L'extension SQLite3 n'est pas activée. Si votre application utilise SQLite, veuillez l'activer.\n";
}

// Étape 1 : Vérifier la version distante
echo "Vérification de la version distante...\n";
$remoteVersion = trim(file_get_contents($versionUrl)); // Télécharger la version distante
if ($remoteVersion === false) {
    die("Erreur : Impossible de récupérer la version distante.\n"); // Arrêter le script si l'URL est inaccessible
}

// Étape 2 : Vérifier la version locale
$localVersion = file_exists($localVersionFile) ? trim(file_get_contents($localVersionFile)) : null; // Lire la version locale si elle existe

if ($localVersion === $remoteVersion) {
    echo "Les fichiers sont déjà à jour. Version actuelle : $localVersion\n";
    exit; // Si la version locale est identique à la version distante, aucune mise à jour n'est nécessaire
} else {
    echo "Mise à jour disponible : $remoteVersion (locale : " . ($localVersion ?? "aucune") . ")\n";
}

// Étape 3 : Sauvegarde des fichiers existants
if (is_dir($extractTo)) {
    $backupDir = __DIR__ . '/backup_' . date('Ymd_His'); // Générer un nom unique pour le dossier de sauvegarde
    echo "Création d'une sauvegarde dans : $backupDir\n";
    mkdir($backupDir, 0755, true); // Créer le dossier de sauvegarde
    recurse_copy($extractTo, $backupDir); // Copier les fichiers existants dans le dossier de sauvegarde
}

// Étape 4 : Téléchargement de l'archive ZIP
echo "Téléchargement du dépôt depuis GitHub...\n";
file_put_contents($tempZipFile, fopen($githubZipUrl, 'r')); // Télécharger l'archive ZIP

// Étape 5 : Extraction des fichiers
echo "Extraction des fichiers...\n";
$zip = new ZipArchive;
if ($zip->open($tempZipFile) === true) {
    $zip->extractTo($extractTo); // Extraire l'archive dans le dossier de destination
    $zip->close();
    echo "Extraction terminée.\n";
} else {
    die("Erreur : Impossible d'extraire les fichiers.\n"); // Arrêter le script en cas d'échec
}

// Étape 6 : Mise à jour du fichier version locale
file_put_contents($localVersionFile, $remoteVersion); // Écrire la nouvelle version dans le fichier local

// Étape 7 : Suppression du fichier ZIP temporaire
unlink($tempZipFile); // Supprimer l'archive ZIP téléchargée

echo "Mise à jour terminée avec succès. Les fichiers sont maintenant à jour dans : $extractTo\n";

/**
 * Fonction utilitaire pour copier un dossier et son contenu
 * @param string $src Chemin source
 * @param string $dst Chemin destination
 */
function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst); // Créer le dossier destination si nécessaire
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file); // Copier récursivement les sous-dossiers
            } else {
                copy($src . '/' . $file, $dst . '/' . $file); // Copier les fichiers individuels
            }
        }
    }
    closedir($dir);
}
?>
