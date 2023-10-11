var confirm = {
    dialog: function(event, title, icon) {
        event.preventDefault();
        const href = event.currentTarget.href;
        Swal.fire({
            text: title,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: 'Evet',
            cancelButtonText: 'İptal',
            allowOutsideClick: false
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = href;
            }
        });
    },
    removeConfirm: function(e, title) {
        confirm.dialog(e, title, 'error');
    },
    editConfirm: function(e, title) {
        confirm.dialog(e, title, 'info');
    },
    listeners: function() {
        const confirmLinks = document.querySelectorAll('[data-confirm]');
        confirmLinks.forEach(function(element, index){
            let title = element.dataset.confirm;
            let type = element.dataset.confirmType;
            element.addEventListener('click', (e)=>
                type === 'remove'
                    ? confirm.removeConfirm(e, title)
                    : confirm.editConfirm(e, title));
        });
    }
};