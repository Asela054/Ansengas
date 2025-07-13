<?php
require_once('../connection/db.php');

$categoryID=$_POST['categoryID'];


?>
<div class="row row-cols-1 row-cols-md-4">
    <?php if ($categoryID == 1 || $categoryID == 2) : ?>
        <div class="col mb-4 groupcategorydiv" id="1">
            <div class="card h-100 shadow-none bg-warning border-warning">
                <div class="card-body p-2 text-center pointer">
                    <h4 class="text-dark font-weight-dark">
                        New</h4>
                    <hr class="border-dark my-1">
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($categoryID == 1) : ?>
        <div class="col mb-4 groupcategorydiv" id="2">
            <div class="card h-100 shadow-none bg-warning border-warning">
                <div class="card-body p-2 text-center pointer">
                    <h4 class="text-dark font-weight-dark">
                        Refill</h4>
                    <hr class="border-dark my-1">
                </div>
            </div>
        </div>
        <div class="col mb-4 groupcategorydiv" id="3">
            <div class="card h-100 shadow-none bg-warning border-warning">
                <div class="card-body p-2 text-center pointer">
                    <h4 class="text-dark font-weight-dark">
                        Empty</h4>
                    <hr class="border-dark my-1">
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
