/**
 * Created by Aldwin on 19.11.2016.
 */
(function($){
    $(document).ready(function(){

        $('#dropzone-preview').sortable({
            opacity: 0.5,
            update:function( event, ui ) {
                $('#dropzone-preview .dz-image-preview').each(function(k,v){
                    $(this).find('.dz-weight input').val(k);
                });
            }
        });
    });
})(jQuery);