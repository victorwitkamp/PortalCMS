<?= $this->layout('layoutLogin', [ 'Geen toegang' ]) ?>
<?= $this->push('head-extra') ?>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
<?= $this->end() ?>
<?= $this->push('body') ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-8">
                <h1>Geen toegang</h1>
            </div>
        </div>
        <p>Geen toegang</p>
        <button onclick="goBack()" class="btn btn-outline-success my-2 my-sm-0"><span class="fa fa-angle-left"></span>
            Ga terug
        </button>
    </div>

<?= $this->end();
