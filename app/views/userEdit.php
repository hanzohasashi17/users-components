<?php $this->layout('layout') ?>

<main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-plus-circle'></i> Создать пользователя
            </h1>

        </div>
        <form action="/userEdit" method="post">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Общая информация</h2>
                            </div>
                            <div class="panel-content">
                                <!-- username -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Имя</label>
                                    <input type="text" id="simpleinput" class="form-control" value="" name="userName">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Address</label>
                                    <input type="text" id="simpleinput" class="form-control" value="" name="address">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Job</label>
                                    <input type="text" id="simpleinput" class="form-control" value="" name="jobTitle">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Phone</label>
                                    <input type="text" id="simpleinput" class="form-control" value="" name="phone">
                                </div>

                                <div>
                                    <input type="hidden" name="id" value=''>
                                </div>

                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-warning">Создать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>