<?php


declare(strict_types=1);

use App\Core\HTTP\Request;
use App\Modules\Members\MemberModel;

//use App\Modules\Members\MemberMapper;

$pageName = 'Wijzigen';
$pageType = 'edit';
$member = MemberModel::getMember((int)$this->request->get('Id'));
if ($member !== null) {
    $allowEdit = true;
    $pageName = 'Lidmaatschap van ' . $member->voornaam . ' ' . $member->achternaam . ' bewerken';
} else {
    return $this->redirectToRoute('errornotfound');
}
?>
<?= $this->layout('layout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>


<?= $this->end() ?>
<?= $this->push('main-content') ?>

    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>
    <div class="container">
        <?php require __DIR__ . '/inc/form.php'; ?>
    </div>

<?= $this->end();
