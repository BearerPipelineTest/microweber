<?php
$post_id = false;

if (isset($params['post_id'])) {
    $post_id = $params['post_id'];
}

$post_tag_id = $params['post_tag_id'];
$filter = [
    'id'=>$post_tag_id,
    'post_id'=>$post_id,
    'single'=>1
];
$tag = db_get('tagging_tagged', $filter);
if ($tag) {
    $post_id = $tag['taggable_id'];
}
if (!$tag) {
    $tag['id'] = false;
    $tag['tag_slug'] = '';
    $tag['tag_name'] = '';
}
?>
<style>
    .demobox {
        position: relative;
        padding: 10px 0px;
    }
    .mw-ui-field {
        width: 100%;
    }
    .helptext{
        color: #666;
    }
</style>

<script>
    $(document).ready(function () {

        $('.js-admin-post-tag-edit-form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: mw.settings.api_url + 'post_tag/edit',
                type: 'post',
                data: $(this).serialize(),
                success: function(data) {
                    var postTagCloud = $('.js-post-tag-' + $('.js-admin-post-tag-edit-form-post-id').val());

                    if (postTagCloud.find('.btn-tag-id-' + data.id).length == 0) {
                        postTagCloud.find('.js-post-tag-add-new').before(getTagPostsButtonHtml(data.id, data.tag_name, data.tag_slug, data.post_id));
                    } else {
                        postTagCloud.find('.btn-tag-id-' + data.id).replaceWith(getTagPostsButtonHtml(data.id, data.tag_name, data.tag_slug, data.post_id));
                    }

                    $('.js-admin-post-tag-edit-form-id').val(data.id);
                    $('.js-admin-post-tag-edit-form-post-id').val(data.post_id);

                    //  mw.reload_module_everywhere('tags');
                    mw.notification.success('<?php _e('Tag is saved!');?>');
                }
            });

        });

    });
</script>

<form method="post" class="js-admin-post-tag-edit-form">

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Tag Name');?></label>
        <input type="text" name="tag_name" value="<?php echo $tag['tag_name']; ?>" class="form-control js-admin-post-tag-edit-form-tag-name">
        <div class="helptext"><?php _e('The name is how it appears on your site.');?></div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Tag Slug');?></label>
        <input type="text" name="tag_slug" value="<?php echo $tag['tag_slug']; ?>" class="form-control js-admin-post-tag-edit-form-tag-slug">
        <div class="helptext"><?php _e('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.');?></div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Tag Description'); ?></label>
        <textarea name="description" class="form-control js-admin-post-tag-edit-form-tag-description"></textarea>
        <div class="helptext"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></div>
    </div>

    <input type="hidden" name="id" class="js-admin-post-tag-edit-form-id" value="<?php echo $tag['id']; ?>" />
    <input type="hidden" name="post_id" class="js-admin-post-tag-edit-form-post-id" value="<?php echo $post_id; ?>" />

    <button class="btn btn-success" type="submit"><i class="mw-icon-web-checkmark"></i> &nbsp; <?php _e('Save Tag');?></button>

</form>

<style>
    .js-admin-post-tags {
        margin-top:20px;
    }
</style>

<div class="js-admin-post-tags"></div>