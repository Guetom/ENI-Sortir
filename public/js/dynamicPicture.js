const profilePicture = document.getElementById('profilePicture');
const pictureInput = document.getElementById('file-upload');

pictureInput.addEventListener('change', function () {
    profilePicture.src = URL.createObjectURL(this.files[0]);
});