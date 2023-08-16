<?= $this->extend('home/layout') ?>

<?= $this->section('heading-sidebar') ?>
    <div class="row heading">
        <div class="col-sm-3 col-xs-3 heading-avatar">
            <div class="heading-avatar-icon">
                <img src="https://bootdey.com/img/Content/avatar/avatar1.png">
            </div>
        </div>
        <div class="col-sm-3 col-xs-3 heading-user-info">
            <p><?= $user->phone ?> (<?= $user->screen_name ?>)</p>
        </div>
        <div class="col-sm-3 col-xs-3 heading-dot pull-right">
            <a href="<?= base_url('logout') ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">
                Logout
            </a>
        </div>
        <!-- <div class="col-sm-1 col-xs-1  heading-dot  pull-right">
            <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
        </div>
        <div class="col-sm-2 col-xs-2 heading-compose  pull-right">
            <i class="fa fa-comments fa-2x  pull-right" aria-hidden="true"></i>
        </div> -->
    </div>
<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
    <?php foreach ($allUsers as $u) : ?>
        <div class="row sideBar">
            <div class="row sideBar-body listChat" user-id='<?= htmlspecialchars($u->encryptedId) ?>' user-phone='<?= $u->phone ?>' user-name='<?= $u->screen_name ?>'>
                <div style="display:flex;justify-content:center;align-items:center;" class="col-sm-1 col-xs-1 sideBar-avatar">
                    <span style="width:25px;height:25px;display:block;background-color:#eee;border-radius:100%;"></span>
                </div>
                <div class="col-sm-11 col-xs-11 sideBar-main">
                    <div class="row">
                        <div class="col-sm-8 col-xs-8 sideBar-name">
                            <span class="name-meta">
                                <?php if ($u->phone === $u->screen_name) {
                                    echo $u->phone;
                                } else {
                                    echo $u->screen_name;
                                } ?>
                            </span>
                            <p class="last-message small"><?= $u->last_message ?>
                        </div>
                        <div class="col-sm-3 col-xs-3 pull-right sideBar-time">
                            <span class="time-meta pull-right">
                                <?php echo $u->last_chat_time ?: "No chat history"; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?= $this->endSection() ?>

<?= $this->section('conversationMobile') ?>
    <div class="row heading">
        <div class="col-sm-1 col-xs-1 chevron" id="backButtonMobile">
            <i class="fa fa-chevron-left fa-2x" aria-hidden="true"></i>
        </div>
        <div class="col-sm-2 col-md-1 col-xs-3 heading-avatar">
            <div class="heading-avatar-icon text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar6.png">
            </div>
        </div>
        <div class="col-sm-7 col-xs-6 heading-name">
            <a class="heading-name-meta" id="recipientNameMobile">
            </a>
            <span class="heading-online">Online</span>
        </div>
        <div class="col-sm-1 col-xs-1  heading-dot pull-right">
            <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i>
        </div>
    </div>
    <div class="row message" id="messageMobile">
        <!-- <div class="row message-previous">
                    <div class="col-sm-12 previous">
                        <a onclick="previous(this)" id="ankitjain28" name="20">
                            Show Previous Message!
                        </a>
                    </div>
                </div> -->
    </div>
    <div class="row reply">
        <div class="col-sm-9 col-xs-9 reply-main">
            <textarea class="form-control" rows="1" id="commentMobile"></textarea>
        </div>
        <div class="col-sm-1 col-xs-1 reply-media" id="send-mediaMobile">
            <form id="media-form" enctype="multipart/form-data">
                <label for="media-upload" class="file-label">
                    <i class="fa fa-paperclip fa-2x" aria-hidden="true"></i>
                </label>
                <input type="file" id="media-upload" style="display:none">
            </form>
        </div>
        <div class="col-sm-1 col-xs-1 reply-send" id='sendMobile'>
            <i class="fa fa-send fa-2x" aria-hidden="true"></i>
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
            <a class="heading-name-meta" id="recipientName">
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
        <div class="col-sm-9 col-xs-9 reply-main">
            <textarea class="form-control" rows="1" id="comment"></textarea>
        </div>
        <div class="col-sm-1 col-xs-1 reply-media" id="send-media">
            <form id="media-form" enctype="multipart/form-data">
                <label for="media-upload" class="file-label">
                    <i class="fa fa-paperclip fa-2x" aria-hidden="true"></i>
                </label>
                <input type="file" id="media-upload" style="display:none">
            </form>
        </div>
        <div class="col-sm-1 col-xs-1 reply-send" id='send-message'>
            <i class="fa fa-send fa-2x" aria-hidden="true"></i>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
    <script type="text/javascript">
        (function() {
            var roomId;
            var currentContactId = null;
            var isLoading = false;
            var dCache = {};
            var $comment = $('#comment');
            var $commentMobile = $('#commentMobile');
            var $sendButton = $('#send-message');
            var $sendButtonMobile = $('#sendMobile');
            var $fileInput = $('send-media');
            var mobileBox = document.getElementById('mobileConversation');

            $sendButton.addClass('disabled');
            $sendButtonMobile.addClass('disabled');
            $comment.addClass('disabled');
            $commentMobile.addClass('disabled');

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

            function extractTime(timestamp) {
                const time = new Date(timestamp);

                const dayOfWeek = time.toLocaleString('en-US', {
                    weekday: 'short'
                });

                const hours = (time.getHours() % 12 || 12);
                const minutes = time.getMinutes();

                const ampm = time.getHours() >= 12 ? 'PM' : 'AM';

                const formattedTime = `${hours}${minutes !== 0 ? ':' + (minutes < 10 ? '0' : '') + minutes : ''} ${ampm}`;

                return formattedTime;
            }

            function groupChatsByTime(chats) {
                var groupedChats = {};

                var todayDate = new Date().toLocaleDateString('en-GB', {
                    day: 'numeric',
                    month: 'numeric',
                    year: 'numeric'
                });

                var yesterdayDate = new Date();
                yesterdayDate.setDate(yesterdayDate.getDate() - 1);
                var yesterdayFormatted = yesterdayDate.toLocaleDateString('en-GB', {
                    day: 'numeric',
                    month: 'numeric',
                    year: 'numeric'
                });

                chats.forEach(function(chat) {
                    var chatTime = new Date(chat.created_at).getTime();
                    var chatDate = new Date(chat.created_at).toLocaleDateString('en-GB', {
                        day: 'numeric',
                        month: 'numeric',
                        year: 'numeric'
                    });
                    
                    var chatDateComponents = chatDate.split('/');
                    var currentTime = new Date().getTime();
                    var timeDiff = currentTime - chatTime;

                    var group;

                    if (chatDate === todayDate) {
                        group = 'Today';
                    } else if (chatDate === yesterdayFormatted) {
                        group = 'Yesterday';
                    } else {
                        var day = parseInt(chatDateComponents[0]).toString();
                        var month = parseInt(chatDateComponents[1]).toString();
                        var year = chatDateComponents[2];

                        chatDate = `${day}/${month}/${year}`;

                        group = chatDate;
                    }

                    if (!groupedChats[group]) {
                        groupedChats[group] = [];
                    }

                    groupedChats[group].push(chat);
                });

                return groupedChats;
            }

            function displayGroupedChats(groupedChats) {
                $('#conversation').html('');

                var sortedGroups = Object.keys(groupedChats).sort(function(a, b) {
                    if (a === 'Today') return -1; // Tetap pertahankan "Today" di atas
                    if (b === 'Today') return 1;
                    if (a === 'Yesterday') return -1; // Kemudian "Yesterday"
                    if (b === 'Yesterday') return 1;
                    return new Date(b) - new Date(a); // Urutan tanggal terbalik (baru ke lama)
                });

                sortedGroups.forEach(function(group) {
                    var chatsInGroup = groupedChats[group];
                    var groupTemplate = `<div class="row message-previous">
                                            <div class="col-sm-12 previous">
                                                ${group}
                                            </div>
                                        </div>`;

                    chatsInGroup.sort(function(a, b) {
                        return new Date(a.created_at).getTime() - new Date(b.created_at).getTime();
                    });

                    chatsInGroup.forEach(function(chat) {
                        var message = chat.message;
                        var media = chat.media; // Assume this is the media URL or null
                        var created_at = chat.created_at;
                        var id_user = chat.id_user;
                        var time = extractTime(chat.created_at);
                        var template = null;
                        // console.log(message);
                        // console.log(media);
                        if (id_user == <?= $idUser ?>) {
                            if (media === '' && message !== '') {
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
                            } else if (media !== '' && message === '') {
                                template = `<div class="row message-body">
                                                <div class="col-sm-12 message-main-sender">
                                                    <div class="sender">
                                                        <div class="message-text">
                                                            <img class="message-media" src="/uploads/` + media + `" alt="Media">
                                                        </div>
                                                        <span class="message-time pull-right">
                                                            ` + time + `
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>`;
                            }
                        } else {
                            if (media === '' && message !== '') {
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
                                            </div>`;
                            } else if (media !== '' && message === '') {
                                template = `<div class="row message-body">
                                                <div class="col-sm-12 message-main-receiver">
                                                    <div class="receiver">
                                                        <div class="message-text">
                                                            <img class="message-media" src="/uploads/` + media + `" alt="Media">
                                                        </div>
                                                        <span class="message-time pull-right">
                                                            ` + time + `
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>`;
                            }
                        }

                        groupTemplate += template;
                    });
                    
                    $('#conversation').prepend(groupTemplate);
                    $('#messageMobile').prepend(groupTemplate);
                    $('#conversation').scrollTop($('#conversation')[0].scrollHeight);
                    $('#messageMobile').scrollTop($('#messageMobile')[0].scrollHeight);
                });
            }

            function getChats() {
                if (isLoading) {
                    setTimeout(getChats, 100);
                    return;
                }

                isLoading = true;

                $.ajax({
                    url: "<?= site_url('home/getChats') ?>",
                    type: 'GET',
                    data: {
                        'roomId': roomId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        isLoading = false;

                        var groupedChats = groupChatsByTime(data);
                        displayGroupedChats(groupedChats);
                    }
                });
            }

            $(document).ready(function() {
                $("#media-upload").change(function() {
                    var formData = new FormData();
                    formData.append("media-upload", $(this)[0].files[0]);

                    $.ajax({
                        url: "<?php echo site_url('home/uploadMedia'); ?>",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            sendMsg(null, data.media);
                        }
                    });
                });
            });

            function sendMsg(message, media) {
                $.ajax({
                    url: "<?php site_url('home/sendMessage'); ?>",
                    type: 'POST',
                    data: {
                        'message': message,
                        'media': media,
                        'id_room': roomId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(media);
                        const currentTimestamp = new Date();
                        const currentTimeFormatted = extractTime(currentTimestamp);

                        const lastDisplayedGroup = $('#conversation .previous:last').text().trim();

                        const lastGroupIsToday = lastDisplayedGroup === 'Today';
                        const currentDate = currentTimestamp.toLocaleDateString('en-GB', {
                            day: 'numeric',
                            month: 'numeric',
                            year: 'numeric'
                        });
                        const isNewDay = !lastGroupIsToday && lastDisplayedGroup !== currentDate;

                        if (isNewDay) {
                            const todayGroupTemplate = `<div class="row message-previous">
                                                            <div class="col-sm-12 previous">
                                                                Today
                                                            </div>
                                                        </div>`;
                            $('#conversation').append(todayGroupTemplate);
                            $('#messageMobile').append(todayGroupTemplate);
                        }

                        var template = null;
                        if (message !== null && media === null) {
                            template = `<div class="row message-body">
                                            <div class="col-sm-12 message-main-sender">
                                                <div class="sender">
                                                    <div class="message-text">
                                                        ` + message + `
                                                    </div>
                                                    <span class="message-time pull-right">
                                                        ` + currentTimeFormatted + `
                                                    </span>
                                                </div>
                                            </div>
                                        </div>`;
                        } else if (message === null && media !== null) {
                            template = `<div class="row message-body">
                                            <div class="col-sm-12 message-main-sender">
                                                <div class="sender">
                                                    <div class="message-text">
                                                        <img class="message-media" src="/uploads/` + media + `" alt="Media">
                                                    </div>
                                                    <span class="message-time pull-right">
                                                        ` + currentTimeFormatted + `
                                                    </span>
                                                </div>
                                            </div>
                                        </div>`;
                        }

                        $('#conversation').append(template);
                        $('#messageMobile').append(template);

                        $('#conversation').scrollTop($('#conversation')[0].scrollHeight);
                        $('#messageMobile').scrollTop($('#messageMobile')[0].scrollHeight);
                    }
                });
            }

            function decr(encryptedId) {
                if (dCache.hasOwnProperty(encryptedId)) {
                    return Promise.resolve(dCache[encryptedId]);
                }
                return $.ajax({
                    url: '<?= site_url('home/decrypt') ?>',
                    type: 'POST',
                    data: {encryptedId: encryptedId},
                    dataType: 'json',
                    async: false
                }).responseJSON.decryptedId;
            }

            $(document).on('click', '.listChat', function() {
                if (isLoading) {
                    return;
                }
                
                isLoading = true;
                
                $comment.removeClass('disabled');
                $commentMobile.removeClass('disabled');
                
                var cId = $(this).attr('user-id');
                var cPhone = $(this).attr('user-phone');
                var cName = $(this).attr('user-name');
                var displayName = (cPhone === cName) ? cPhone : cName;
                var decUserId = decr(cId);
                // console.log(decUserId);

                if(window.innerWidth <= 700){
                    mobileBox.style.display = 'block';
                }

                if (currentContactId === decUserId) {
                    isLoading = false;
                    return;
                }

                currentContactId = decUserId;

                $('#conversation').html('');
                $('#recipientName').html(displayName);
                $('#recipientNameMobile').html(displayName);
                $('#messageMobile').html('');

                $.ajax({
                    url: "<?= site_url('home/getRoom') ?>",
                    type: 'GET',
                    data: {
                        'contactId': decUserId
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        roomId = data.id;
                        isLoading = false;
                        getChats();
                    }
                });
            });
            
            function toggleBtn() {
                if ($comment.val().trim().length > 0) {
                    $sendButton.removeClass('disabled');
                } else {
                    $sendButton.addClass('disabled');
                }
            }
            
            function toggleBtnMobile() {
                if ($commentMobile.val().trim().length > 0) {
                    $sendButtonMobile.removeClass('disabled');
                } else {
                    $sendButtonMobile.addClass('disabled');
                }
            }
            
            $sendButton.on('click', function() {
                var message = $comment.val().trim();
                $comment.val('');
                sendMsg(message, null);
                $sendButton.addClass('disabled');
            }), 

            $sendButtonMobile.on('click', function() {
                var message = $commentMobile.val().trim();
                $commentMobile.val('');
                sendMsg(message, null);
                $sendButtonMobile.addClass('disabled');
            }), 

            $comment.on('input', function() {
                toggleBtn();
            }), 

            $commentMobile.on('input', function() {
                toggleBtnMobile();
            }), 

            $('#backButtonMobile').on('click', function() {
                if (window.innerWidth <= 700) {
                    mobileBox.style.display = 'none';
                }
            });

        })();
    </script>
<?= $this->endSection('script') ?>