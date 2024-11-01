/* global grunionEditorView, tinyMCE, QTags, wp */
( function ($, wp) {

    wp.mce = wp.mce || {};
    if ('undefined' === typeof wp.mce.views) {
        return;
    }

    var $wp_content_wrap = $('#wp-content-wrap');

    $('#insert-vg').on('click', function (e) {
        var text = '';
        text = getSelection( $('#content') );
        if (!text && $wp_content_wrap.hasClass('tmce-active')) {
            text = tinyMCE.activeEditor.selection.getContent()
        }
        if (text && text.length > 0) {
            $("#custom").prop("checked", true);
            $("#custom_trigger_text").val(text);
        } else {
            $("#thumbnail").prop("checked", true);
        }
        $('.vg_overlay').fadeIn(0);
        openFields();
    });

    function getSelection( textarea )
    {
        var selection = null;

        selection = getSelectionText();
        if (selection) {
            return selection;
        }

        if (typeof(textarea.prop('selectionEnd')) != "undefined" ) {
            selection = (textarea.val()).substring(textarea.prop('selectionStart'), textarea.prop('selectionEnd'));
        }

        return selection;
    }

    function getSelectionText() {
        var text = "";
        if (window.getSelection) {
            text = window.getSelection().toString();
        } else if (document.selection && document.selection.type != "Control") {
            text = document.selection.createRange().text;
        }
        return text;
    }

    $('.vg_overlay ').click(function (e){
        var div = $(".vg_overlay_content");
        if (!div.is(e.target) && div.has(e.target).length === 0) {
            $(this).fadeOut(0);
        }
    });

    $('.edits').change(function () {
        openFields();
    });

    function openFields() {
    $('#embed_type_select').on("change",function() {
        var selectVal =  $(this).val();
       $("div[data-show]").slideUp();
       $("div[data-show="+selectVal+"]").slideDown();
     });
    }

    $("#height").bind("keyup change", function (e) {
        var width = parseInt($('#height').val() * 1.5);
        $('#width').val(width);
    })

    $("#width").bind("keyup change", function (e) {
        var height = parseInt($('#width').val() / 1.5);
        $('#height').val(height);
    })

    $("#submit-form").click(function (event) {
        
        $('.information_box .message').hide();
        $(".message .bg-danger" ).text('');
        
        var data = $('#vg-edit-form').serializeArray();

        var campaign_error = false;
        var input = ''
        $.each( data, function( key, obj ) {
            if (obj.name == 'cid' && (!obj.value || obj.value == 0)) {
                campaign_error = true;
            }
            input += ' '+obj.name+'="'+obj.value+'"';
        });
        
        if (campaign_error === true) {
            $('.information_box .message').show();
            $( ".message .bg-danger" ).append('Please choose a Campaign!');
            return false;
        }

        event.preventDefault();
        if ($wp_content_wrap.hasClass('tmce-active')) {
            tinyMCE.execCommand('vg_command', input);
        } else if ($wp_content_wrap.hasClass('html-active')) {
            QTags.insertContent('[venby '+input+']');
        } else {
            window.console.error('Neither TinyMCE nor QuickTags is active. Unable to insert form.');
        }

        $('.vg_overlay').fadeOut(0);
    });


    var options = {
        color: false,
        mode: '',
        controls: {
            horiz: 's',
            vert: 'l',
            strip: 'h'
        },
        hide: true,
        border: true,
        target: false,
        width: 200,
        palettes: true
    };
    $('.vg_overlay .color-field').each(function(){
        $(this).wpColorPicker(options);
    });
    $('#vg-edit-form .wp-picker-container .wp-color-result.button').click(function(){
        $(this).each(function(){

            var curButton = $(this);
            var cur = $(this).children('span.wp-color-result-text');


            var parentHolder = $(this).parent('.wp-picker-container');
            var childrenHolder = parentHolder.children('.wp-picker-holder');


            var curInputWrap = parentHolder.children('span.wp-picker-input-wrap');
            $(document).mouseup(function (e){
                var div = cur;
                if (!div.is(e.target)
                    && div.has(e.target).length === 0
                    && !childrenHolder.is(e.target)
                    && childrenHolder.has(e.target).length === 0
                    && !curButton.is(e.target)
                    && !curInputWrap.is(e.target)
                    && curInputWrap.has(e.target).length === 0
                ) {
                    cur.removeClass('defVisible');
                }
            });
            $(this).children('span.wp-color-result-text').toggleClass('defVisible');
        });
    });


    $('.vg_overlay input[name="bbr"]').on('change', function(){
        if($(this).is(':checked')){
            $('#btn-border-color').css('display', 'block');
        }else{
            $('#btn-border-color').css('display', 'none');
        }
    });

}(jQuery, wp) );
