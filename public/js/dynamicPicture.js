const previewHandler = document.querySelector('.dynamic-picture-handler');
const dynamicPicture = document.querySelector('.dynamic-picture');

if(previewHandler && dynamicPicture) {
    previewHandler.addEventListener('change', function() {
        previewImage(this);
    });

    function previewImage(input) {

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                dynamicPicture.src = e.target.result;
                dynamicPicture.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
}
