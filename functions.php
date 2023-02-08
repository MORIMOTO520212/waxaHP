<?php
function meta_box() {
    add_meta_box(
        'my_activity_box',
        '活動内容',
        'my_activity_box',
        'dashboard',
        'normal',
        'default'
    );
    add_meta_box(
        'my_information_box',
        'サークル情報',
        'my_information_box',
        'dashboard',
        'normal',
        'default'
    );
    add_meta_box(
        'my_sns_box',
        'SNSの設定',
        'my_sns_box',
        'dashboard',
        'normal',
        'default'
    );
    add_meta_box(
        'my_gallery_box',
        'ギャラリーの設定',
        'my_gallery_box',
        'dashboard',
        'normal',
        'default'
    );
}
add_action( 'admin_menu', 'meta_box' );

function my_activity_box() {
?>
    <style>
        #mybox .input-text-wrap {
            margin-bottom: 12px;
        }
        #mybox label {
            display: inline-block;
            margin-bottom: 4px;
        }
        #mybox .submit {
            display: flex;
            width: 100%;
            justify-content: end;
        }
    </style>
    <form id="mybox" action="" method="post">
        <div class="textarea-wrap">
            <label for="activity">ここへ活動内容を記入してください</label>
            <textarea name="activity_text" rows="5" placeholder="ここへ活動内容を記入してください。"><?php echo get_post_meta( 1, 'activity_text', true ); ?></textarea>
        </div>
        <div class="submit">
            <input type="hidden" name="submit_type" value="activity">
            <input type="submit" class="button button-primary" value="保存する">
        </div>
    </form>
<?php
}

function my_information_box() {
    ?>
    <style>
        #mybox .input-text-wrap {
            margin-bottom: 12px;
        }
        #mybox label {
            display: inline-block;
            margin-bottom: 4px;
        }
        #mybox .submit {
            display: flex;
            width: 100%;
            justify-content: end;
        }
    </style>
    <form id="mybox" action="" method="post">
        <div class="input-text-wrap">
            <label for="input1">人数</label>
            <input type="text" id="input1" name="members" value="<?php echo get_post_meta( 1, 'members', true ); ?>" placeholder="">
        </div>
        <div class="input-text-wrap">
            <label for="input2">他大学の参加について</label>
            <input type="text" id="input2" name="other_univ" value="<?php echo get_post_meta( 1, 'other_univ', true ); ?>" placeholder="">
        </div>
        <div class="input-text-wrap">
            <label for="input2">設立年</label>
            <input type="text" id="input2" name="establish_year" value="<?php echo get_post_meta( 1, 'establish_year', true ); ?>" placeholder="">
        </div>
        <div class="input-text-wrap">
            <label for="input3">年会費</label>
            <input type="text" id="input3" name="membership_fee" value="<?php echo get_post_meta( 1, 'membership_fee', true ); ?>" placeholder="">
        </div>
        <div class="submit">
            <input type="hidden" name="submit_type" value="information">
            <input type="submit" class="button button-primary" value="保存する">
        </div>
    </form>
<?php
}


function my_sns_box() {
?>
    <style>
        #mybox .input-text-wrap {
            margin-bottom: 12px;
        }
        #mybox label {
            display: inline-block;
            margin-bottom: 4px;
        }
        #mybox .submit {
            display: flex;
            width: 100%;
            justify-content: end;
        }
    </style>
    <form id="mybox" action="" method="post">
        <div class="input-text-wrap">
            <label for="instagram_id">Instagram ユーザー名</label>
            <input type="text" id="instagram_id" name="instagram_id" value="<?php echo get_post_meta( 1, 'instagram_id', true ); ?>">
        </div>
        <div class="input-text-wrap">
            <label for="twitter_id">Twitter ユーザー名</label>
            <input type="text" id="twitter_id" name="twitter_id" value="<?php echo get_post_meta( 1, 'twitter_id', true ); ?>">
        </div>
        <div class="submit">
            <input type="hidden" name="submit_type" value="sns">
            <input type="submit" class="button button-primary" value="保存する">
        </div>
    </form>
<?php
}


function my_gallery_box() {
?>
    <style>
        #galleries {
            height: 350px;
            overflow-y: scroll;
        }
        img.gallery_img {
            width: 70%;
            height: auto;
        }
        div.imagebox {
            width: 75%;
            margin: 0 auto;
        }
        #gallerybox .submit {
            display: flex;
            width: 100%;
            justify-content: end;
        }
    </style>

    <form id="gallerybox" action="" method="post">
        <div id="galleries">
            <?php
            $get_galleries =  maybe_unserialize( get_post_meta( 1, 'galleries', true ) ) ?: [];
            $args = array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_status' => 'any',
                'posts_per_page' => -1
                );
            $attachments = get_posts( $args );
            foreach ( $attachments as $i => $data ) {
                // $data->ID, $data-guid
                ?>
                <div class="imagebox">
                    <input type="checkbox" id="<?php echo $i ?>" name="<?php echo "galleries[]" ?>"
                     value="<?php echo $data->ID ?>" <?php echo in_array( $data->ID, $get_galleries, false ) ? 'checked': '' ?>>
                    <label for="<?php echo $i ?>">
                        <img class="gallery_img" src="<?php echo $data->guid ?>">
                    </label>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="submit">
            <input type="hidden" name="submit_type" value="gallery">
            <input type="submit" class="button button-primary" value="保存する">
        </div>
    </form>

<?php
}


// メールを送信する
function my_sendmail( $subject, $message ) {

    // サイト管理情報を取得する
    $admin_email = get_option('admin_email');
    $site_name = get_option('blogname');

    // 無害化
    $subject = sanitize_text_field( $subject );

    if ( $site_name ) {
        $headers = "From: {$site_name} <{$admin_email}>\r\n";
        wp_mail( $admin_email, $subject, $message, $headers );
    } else {
        return false;
    }

    return true;
}

// メール送信完了アラート
if ( isset( $_GET['t'] ) && $_GET['t'] === 'sended' ) {
    echo '<script>alert("メール送信が完了しました。");</script>';
}

add_action( 'after_setup_theme', function() {
    if ( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'sns' ) {
        update_post_meta( 1, 'instagram_id', $_POST['instagram_id'] );
        update_post_meta( 1, 'twitter_id', $_POST['twitter_id'] );
    }
    // 活動内容更新
    if ( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'activity' ) {
        update_post_meta( 1, 'activity_text', $_POST['activity_text'] );
    }
    // インフォーメーション更新
    if ( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'information' ) {
        update_post_meta( 1, 'members',        $_POST['members']        );
        update_post_meta( 1, 'other_univ',     $_POST['other_univ']     );
        update_post_meta( 1, 'establish_year', $_POST['establish_year'] );
        update_post_meta( 1, 'membership_fee', $_POST['membership_fee'] );
    }
    // ギャラリー更新
    if ( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'gallery' ) {
        if ( isset( $_POST['galleries'] ) )
            update_post_meta( 1, 'galleries', maybe_serialize( $_POST['galleries'] ) );
    }
    // お問い合わせフォーム
    if ( isset( $_POST['submit_type'] ) && $_POST['submit_type'] === 'contact' ) {
        if( !wp_verify_nonce( $_POST['nonce'], 'sDio33kls673df' ) ) return;
        $name =    sanitize_text_field( $_POST['_name']   );
        $email =   sanitize_text_field( $_POST['email']   );
        $request = sanitize_text_field( $_POST['request'] );
        $body =    sanitize_text_field( $_POST['body']    );

        $subject = "{$name} 様からお問い合わせがありました。";

        $message = "
        {$name} 様からお問い合わせがありました。
        ご要望：{$request}
        メールアドレス：{$email}
        
        --------------メール本文--------------
        {$body}
        ";

        my_sendmail( $subject, $message );

        wp_redirect( home_url('/?t=sended') );
        exit;
    }
});

?>