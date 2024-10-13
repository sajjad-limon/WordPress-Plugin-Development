;(function($){
    $(document).ready(function(){

        var modal = new PlainModal(document.getElementById('modal-content'));
        modal.closeButton = document.getElementById('close-button');
        modal.open();

    });
    
})(jQuery);