<?php $this->layout('layout') ?>
<?php
session_start();
d($isLogged);
if (!$isLogged) {
    header('Location: /login');
}
?>
<main id="js-page-content" role="main" class="page-content mt-3">
            <div class="subheader">
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-users'></i> Список пользователей
                </h1>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <a class="btn btn-success" href="/createUser">Добавить</a>

                    <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                        <input type="text" id="js-filter-contacts" name="filter-contacts" class="form-control shadow-inset-2 form-control-lg" placeholder="Найти пользователя">
                        <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                            <label class="btn btn-default active">
                                <input type="radio" name="contactview" id="grid" checked="" value="grid"><i class="fas fa-table"></i>
                            </label>
                            <label class="btn btn-default">
                                <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="js-contacts">
                <?php foreach ($users as $user): ?>
                <div class="col-xl-4">
                    <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="<?= $this->e($user['username']) ?>">
                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                            <div class="d-flex flex-row align-items-center">
                                <span class="status status-<?= $this->e($user['status']) ?> mr-3">
                                    <span class="rounded-circle profile-image d-block " style="background-image:url('<?= $this->e($user['image']) ?>'); background-size: cover;"></span>
                                </span>
                                <div class="info-card-text flex-1">
                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                        <?= $this->e($user['username']) ?>
                                    </a>
                                    <?php if ($this->e($isAdmin) === 'admin' || (int)$this->e($loggedUserId) === $user['id']): ?>
                                        <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                            <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="/editUserProfile/<?= $this->e($user['id']) ?>">
                                                <i class="fa fa-edit"></i>
                                            Редактировать</a>
                                            <a class="dropdown-item" href="/editUserSecurity/<?= $this->e($user['id']) ?>">
                                                <i class="fa fa-lock"></i>
                                            Безопасность</a>
                                            <a class="dropdown-item" href="/editUserStatus/<?= $this->e($user['id']) ?>">
                                                <i class="fa fa-sun"></i>
                                            Установить статус</a>
                                            <a class="dropdown-item" href="/editUserMedia/<?= $this->e($user['id']) ?>">
                                                <i class="fa fa-camera"></i>
                                                Загрузить аватар
                                            </a>
                                            <a href="deleteUser/<?= $this->e($user['id']) ?>" class="dropdown-item" onclick="return confirm('are you sure?');">
                                                <i class="fa fa-window-close"></i>
                                                Удалить
                                            </a>
                                        </div>
                                    <?php endif ?>
                                    <span class="text-truncate text-truncate-xl"><?= $this->e($user['jobTitle']) ?></span>
                                </div>
                                <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                                    <span class="collapsed-hidden">+</span>
                                    <span class="collapsed-reveal">-</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 collapse show">
                            <div class="p-3">
                                <a href="<?= $this->e($user['phone']) ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                    <i class="fas fa-mobile-alt text-muted mr-2"></i><?= $this->e($user['phone']) ?></a>
                                <a href="mailto:<?= $this->e($user['email']) ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                    <i class="fas fa-mouse-pointer text-muted mr-2"></i><?= $this->e($user['email']) ?></a>
                                <address class="fs-sm fw-400 mt-4 text-muted">
                                    <i class="fas fa-map-pin mr-2"></i><?= $this->e($user['address']) ?></address>
                                <div class="d-flex flex-row">
                                    <a href="<?= $this->e($user['vk']) ?>;" class="mr-2 fs-xxl" style="color:#4680C2">
                                        <i class="fab fa-vk"></i>
                                    </a>
                                    <a href="<?= $this->e($user['telegram']) ?>" class="mr-2 fs-xxl" style="color:#38A1F3">
                                        <i class="fab fa-telegram"></i>
                                    </a>
                                    <a href="<?= $this->e($user['instagram']) ?>" class="mr-2 fs-xxl" style="color:#E1306C">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </main>