var message = {
    listeners: function() {
        const messageElements = document.querySelectorAll('[data-message]');
        messageElements.forEach(function(element, index){
            let when = element.dataset.dismiss;
            if(when !== undefined) {
                setTimeout(()=> {
                    element.remove()
                }, (when * 1000));
            }
        })
    }
}