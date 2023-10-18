const profile = {
    setRecoveryCodes: function(response) {
        const container = document.getElementById('recovery_codes');
        let input = '';
        response.data.forEach((value, index) => {
            input += value + "\n";
        })
        container.innerHTML = '<textarea rows="10" disabled>'+ input +'</textarea>';
    }
}

document.addEventListener('DOMContentLoaded', function(){
    const showbutton = document.getElementById('show_recovery_codes');
    const generateButton = document.getElementById('generate_recovery_codes');
    showbutton.addEventListener('click', (e)=> {
        axios.post('/admin/profile/2fa/recovery-codes')
            .then(function (response) {
                profile.setRecoveryCodes(response);
            })
            .catch(function (error) {
                console.log("Error: " + error.message );
            })
    });

    generateButton.addEventListener('click', (e)=> {
        axios.post('/admin/profile/2fa/generate-recovery-codes')
            .then(function (response) {
                profile.setRecoveryCodes(response);
            })
            .catch(function (error) {
                console.log("Error: " + error.message );
            })
    });

});