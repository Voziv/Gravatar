<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gravatar Library Test</title>
    <!-- Yup, we're using bootstrap for the demo. It's too bad if you don't like it. -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <style>
        .avatar-example,
        .avatar-example th {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>lrobert\Gravatar
            <small>Example #1</small>
        </h1>
    </div>

    <p class="lead">This example just showcases the various avatars available through Gravatar.</p>

    <p>Hashes are used instead of emails for this example.</p>

    <?php

    use lrobert\Gravatar\Gravatar;

    $loader = @include_once('../vendor/autoload.php');

    if (!$loader) {
        echo '<div class="alert alert-danger"><strong>Error:</strong> Could not load the auto-loader. Did you remember to run "composer install"?</div>';
        die();
    }

    $gravatar = new Gravatar();

    $emailHashes = array(
        '8ec714ac9c4d5e6c61727f6fe2d2ad85',
        '9f11eadaf395613691a0b76a5a6b65f9',
    );

    $listOfDefaultImages = array(
        Gravatar::FALLBACK_DEFAULT,
        Gravatar::FALLBACK_IDENTICON,
        Gravatar::FALLBACK_MONSTER_ID,
        Gravatar::FALLBACK_MYSTERY_MAN,
        Gravatar::FALLBACK_RETRO,
        Gravatar::FALLBACK_WAVATAR,
    );

    ?>

    <table class="avatar-example table table-condensed table-bordered">
        <thead>
        <tr>
            <th>User Avatar</th>
            <th>Default</th>
            <th>Identicon</th>
            <th>Monster ID</th>
            <th>Mystery Man</th>
            <th>Retro</th>
            <th>Wavatar</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($emailHashes as $hash) : ?>
            <tr>
                <?php $gravatar->setForceDefaultImage(false); ?>
                <?php $gravatar->setDefaultImage(Gravatar::FALLBACK_404); ?>
                <td class="avatar">
                    <img src="<?= $gravatar->getUrl($hash, false) ?>" alt="User Gravatar">
                </td>

                <?php $gravatar->setForceDefaultImage(true); ?>
                <?php foreach ($listOfDefaultImages as $fallback) : ?>
                    <?php $gravatar->setDefaultImage($fallback); ?>
                    <td class="avatar">
                        <img src="<?= $gravatar->getUrl($hash, false) ?>" alt="Forced Default Avatar">
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>