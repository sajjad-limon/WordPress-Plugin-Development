/* ;(function($){
    $(document).ready(function(){
        PlainModal.closeByEscKey = false;
        PlainModal.closeByOverlay = false;
        var modalels = document.querySelectorAll(".modal-content");
        var modals = [];
        for(i=0;i<modalels.length;i++){
            var content = modalels[i];
            modals[i] = new PlainModal(content);
            modals[i].closeButton = content.querySelector('.close-button');
            modals[i].open();
        }


    });
})(jQuery); */


;(function($){
    $(document).ready(function(){

        var modal = new PlainModal(document.getElementsByClassName('modal-content'));
        modal.closeButton = document.getElementsByClassName('close-button');
        modal.open();

    });
    
})(jQuery);