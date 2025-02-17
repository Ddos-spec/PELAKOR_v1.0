<div class="progress-tracker">
    <?php
    $statuses = [
        'Menunggu Konfirmasi' => 1,
        'Penjemputan' => 2,
        'Diterima' => 3,
        'Sedang di Cuci' => 4,
        'Sedang Di Jemur' => 5,
        'Sedang di Setrika' => 6,
        'Quality Control' => 7,
        'Siap Diantar' => 8,
        'Selesai' => 9
    ];
    
    $currentStep = $statuses[$cucian['status_cucian']] ?? 0;
    ?>
    
    <ul class="stepper">
        <?php foreach($statuses as $status => $step): ?>
        <li class="step <?= $step <= $currentStep ? 'active' : '' ?>">
            <div class="step-content">
                <span class="step-number"><?= $step ?></span>
                <span class="step-text"><?= $status ?></span>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
