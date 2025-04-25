document.addEventListener('DOMContentLoaded', function () {
    let deleteB = document.querySelectorAll('.delete-btn');
    let deleteCon = document.getElementById('delete-confirmation');
    let CDB = document.getElementById('confirm-delete');
    let CancelDB = document.getElementById('cancel-delete');
    let D = document.getElementById('delete-form');
    let DNid = document.getElementById('delete-notfication-id');
    let DN = null;

    deleteB.forEach(button => {
        button.addEventListener('click', function () {
            let noti = this.closest('[data-notfication-id]');
            DN = noti.getAttribute('data-notfication-id');
            DNid.value = DN;
            deleteCon.classList.remove('d-none');
        });
    });

    CDB.addEventListener('click', function () {
        if (DN) {
            D.submit();
        }
    });

    CancelDB.addEventListener('click', function () {
        deleteCon.classList.add('d-none');
        DNid.value = '';
        DN = null;
    });

    let DAB = document.getElementById('delete-all-btn');
    let DACon = document.getElementById('delete-all-confirmation');
    let CDAB = document.getElementById('confirm-delete-all');
    let CancelDAB = document.getElementById('cancel-delete-all');
    let DAA = document.getElementById('delete-all-form');

    if (DAB) {
        DAB.addEventListener('click', function () {
            DACon.classList.remove('d-none');
        });
    }

    if (CDAB) {
        CDAB.addEventListener('click', function () {
            DAA.submit();
        });
    }

    if (CancelDAB) {
        CancelDAB.addEventListener('click', function () {
            DACon.classList.add('d-none');
        });
    }
});
