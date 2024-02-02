const previewHandler = document.querySelector('input[type=file].dynamic-picture-handler');
const dynamicPicture = document.querySelector('.dynamic-picture');

if (previewHandler && dynamicPicture) {
    previewHandler.addEventListener('change', function () {
        previewImage(this);
    });

    dynamicPicture.addEventListener('click', function () {
        previewHandler.click();
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                dynamicPicture.src = e.target.result;
                dynamicPicture.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
}