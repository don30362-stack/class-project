<?php

const REGISTER_UPLOADS_SESSION_KEY = 'registration_uploads';

function isRegistrationAvatarFilename($value): bool
{
    return is_string($value)
        && basename($value) === $value
        && preg_match('/\A[0-9a-f]{32}\.(?:jpg|png|gif)\z/D', $value) === 1;
}

function registrationUploadFilename(): ?string
{
    $fileName = $_SESSION[REGISTER_UPLOADS_SESSION_KEY] ?? null;

    return isRegistrationAvatarFilename($fileName) ? $fileName : null;
}

function replaceRegistrationUpload(string $fileName): ?string
{
    if (!isRegistrationAvatarFilename($fileName)) {
        throw new InvalidArgumentException('Invalid registration avatar filename.');
    }

    $previousFileName = registrationUploadFilename();
    $_SESSION[REGISTER_UPLOADS_SESSION_KEY] = $fileName;

    return $previousFileName;
}

function releaseRegistrationUpload(string $fileName): void
{
    if (registrationUploadFilename() === $fileName) {
        unset($_SESSION[REGISTER_UPLOADS_SESSION_KEY]);
    }
}

function deleteUnusedRegistrationAvatar(PDO $link, string $fileName, string $uploadDirectory): bool
{
    if (!isRegistrationAvatarFilename($fileName)) {
        return false;
    }

    $memberCheck = $link->prepare('SELECT 1 FROM member WHERE imgname = :imgname LIMIT 1');
    $memberCheck->execute(array(':imgname' => $fileName));
    if ($memberCheck->fetchColumn() !== false) {
        return false;
    }

    $targetPath = rtrim($uploadDirectory, '/\\') . DIRECTORY_SEPARATOR . $fileName;
    if (!is_file($targetPath)) {
        return true;
    }

    return unlink($targetPath);
}
