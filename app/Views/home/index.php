<?= $this->extend('home/layout') ?>

<?= $this->section('heading-sidebar') ?>
    <div class="row heading">
        <div class="col-sm-3 col-xs-3 heading-avatar">
            <div class="heading-avatar-icon">
                <img src="https://bootdey.com/img/Content/avatar/avatar1.png">
            </div>
        </div>
        <div class="col-sm-3 col-xs-3 heading-dot pull-right">
            <a href="<?= base_url('logout') ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">
                Logout
            </a>
        </div>
        <div class="col-sm-1 col-xs-1  heading-dot  pull-right">
            <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
        </div>
        <div class="col-sm-2 col-xs-2 heading-compose  pull-right">
            <i class="fa fa-comments fa-2x  pull-right" aria-hidden="true"></i>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
    <div class="row searchBox">
        <div class="col-sm-12 searchBox-inner">
            <div class="form-group has-feedback">
                <input id="searchText" type="text" class="form-control" name="searchText" placeholder="Search">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>
    </div>
    <div class="row sideBar">
        <div class="row sideBar-body">
            <div class="col-sm-3 col-xs-3 sideBar-avatar">
                <div class="avatar-icon">
                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png">
                </div>
            </div>
            <div class="col-sm-9 col-xs-9 sideBar-main">
                <div class="row">
                    <div class="col-sm-8 col-xs-8 sideBar-name">
                        <span class="name-meta">John Doe
                        </span>
                    </div>
                    <div class="col-sm-4 col-xs-4 pull-right sideBar-time">
                        <span class="time-meta pull-right">18:18
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('conversation') ?>
    <div class="row heading">
        <div class="col-sm-2 col-md-1 col-xs-3 heading-avatar">
            <div class="heading-avatar-icon">
                <img src="https://bootdey.com/img/Content/avatar/avatar6.png">
            </div>
        </div>
        <div class="col-sm-8 col-xs-7 heading-name">
            <a class="heading-name-meta" id="recipientName"> Siapa aja boleh
            </a>
            <span class="heading-online">Online</span>
        </div>
        <div class="col-sm-1 col-xs-1  heading-dot pull-right">
            <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
        </div>
    </div>
    <div class="row message" id="conversation">
        <!-- <div class="row message-previous">
                    <div class="col-sm-12 previous">
                        <a onclick="previous(this)" id="ankitjain28" name="20">
                            Show Previous Message!
                        </a>
                    </div>
                </div> -->
    </div>
    <div class="row reply">
        <div class="col-sm-1 col-xs-1 reply-emojis">
            <i class="fa fa-smile-o fa-2x"></i>
        </div>
        <div class="col-sm-9 col-xs-9 reply-main">
            <textarea class="form-control" rows="1" id="comment"></textarea>
        </div>
        <div class="col-sm-1 col-xs-1 reply-recording">
            <i class="fa fa-microphone fa-2x" aria-hidden="true"></i>
        </div>
        <div class="col-sm-1 col-xs-1 reply-send" id='send-message'>
            <i class="fa fa-send fa-2x" aria-hidden="true"></i>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('contact') ?>
<?php foreach ($allUsers as $u) : ?>
    <div class="row sideBar-body contact" user-id='<?= htmlspecialchars($u->encryptedId) ?>' user-name='<?= $u->phone ?> (<?= $u->screen_name ?>)'>
        <div class="col-sm-3 col-xs-3 sideBar-avatar">
            <div class="avatar-icon">
                <img src="https://bootdey.com/img/Content/avatar/avatar1.png">
            </div>
        </div>
        <div class="col-sm-9 col-xs-9 sideBar-main">
            <div class="row">
                <div class="col-sm-8 col-xs-8 sideBar-name">
                    <span class="name-meta"> <?= $u->phone ?> (<?= $u->screen_name ?>)
                    </span>
                </div>
                <div class="col-sm-4 col-xs-4 pull-right sideBar-time">
                    <span class="time-meta pull-right">18:18
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var roomId;
            var currentContactId = null;
            var decryptedUserIdCache = {};
            var $comment = $('#comment');
            var $sendButton = $('#send-message');

            $sendButton.addClass('disabled');

            // function isBase64(str) {
            //     try {
            //         return btoa(atob(str)) === str;
            //     } catch (err) {
            //         return false;
            //     }
            // }

            // function decryptUserId(encryptedId) {
            //     return isBase64(encryptedId) ? atob(encryptedId) : null;
            // }

            function decryptUserId(encryptedId) {
                if (decryptedUserIdCache.hasOwnProperty(encryptedId)) {
                    return Promise.resolve(decryptedUserIdCache[encryptedId]);
                }
                return $.ajax({
                    url: '<?= site_url('home/decrypt') ?>',
                    type: 'POST',
                    data: {encryptedId: encryptedId},
                    dataType: 'json',
                    async: false
                }).responseJSON.decryptedId;
            }

            function extractTime(timestamp) {
                const time = new Date(timestamp);

                const dayOfWeek = time.toLocaleString('en-US', {
                    weekday: 'short'
                });
                const hours = time.getHours().toString().padStart(2, '0');
                const minutes = time.getMinutes().toString().padStart(2, '0');

                return `${dayOfWeek}, ${hours}:${minutes}`;
            }
            
            function getChats() {
                $.ajax({
                    url: "<?= site_url('home/getChats') ?>",
                    type: 'GET',
                    data: {
                        'roomId': roomId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        for (var i = 0; i < data.length; i++) {
                            var message = data[i].message;
                            var created_at = data[i].created_at;
                            var id_user = data[i].id_user;
                            var time = extractTime(data[i].created_at);
                            var template = null;
                            if (id_user == <?= $idUser ?>) {
                                template = `<div class="row message-body">
                                                <div class="col-sm-12 message-main-sender">
                                                    <div class="sender">
                                                        <div class="message-text">
                                                            ` + message + `
                                                        </div>
                                                        <span class="message-time pull-right">
                                                            ` + time + `
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>`;
                            } else {
                                template = `<div class="row message-body">
                                                <div class="col-sm-12 message-main-receiver">
                                                    <div class="receiver">
                                                        <div class="message-text">
                                                            ` + message + `
                                                        </div>
                                                        <span class="message-time pull-right">
                                                            ` + time + `
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>`
                            }
                            $('#conversation').append(template);
                            $('#conversation').scrollTop($('#conversation')[0].scrollHeight);
                        }
                    }
                });
            }

            $('.contact').on('click', function() {
                var contactId = $(this).attr('user-id');
                var contactName = $(this).attr('user-name');
                var decryptedUserId = decryptUserId(contactId);
                // console.log(decryptedUserId);

                if (currentContactId === decryptedUserId) {
                    return;
                }

                currentContactId = decryptedUserId;

                $('#conversation').html('');
                $('#recipientName').html(contactName);

                $.ajax({
                    url: "<?= site_url('home/getRoom') ?>",
                    type: 'GET',
                    data: {
                        'contactId': decryptedUserId
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        roomId = data.id;
                        getChats();
                    }
                });
            });

            function sendMessage(message) {
                $.ajax({
                    url: "<?php site_url('home/sendMessage'); ?>",
                    type: 'POST',
                    data: {
                        'message': message,
                        'id_room': roomId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        const currentTimestamp = new Date();
                        const currentTimeFormatted = extractTime(currentTimestamp);
                        var template = `<div class="row message-body">
                                            <div class="col-sm-12 message-main-sender">
                                                <div class="sender">
                                                    <div class="message-text">
                                                        ` + data.message + `
                                                    </div>
                                                    <span class="message-time pull-right">
                                                        ` + currentTimeFormatted + `
                                                    </span>
                                                </div>
                                            </div>
                                        </div>`;
                        $('#conversation').append(template);
                    }
                });
            }

            $sendButton.on('click', function() {
                var message = $comment.val().trim();
                $comment.val('');
                sendMessage(message);
                $sendButton.addClass('disabled');
            });

            function toggleSendButton() {
                if ($comment.val().trim().length > 0) {
                    $sendButton.removeClass('disabled');
                } else {
                    $sendButton.addClass('disabled');
                }
            }

            $comment.on('input', function() {
                toggleSendButton();
            });

        });
    </script>
<?= $this->endSection('script') ?>