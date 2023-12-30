function imgPreview(inputParam, imgParam) {
  const form = document.getElementById(inputParam);
  const preview = document.getElementById(imgParam);
  const image = form.files[0];
  if (image) {
    const imageURL = URL.createObjectURL(image);
    preview.src = imageURL;
  }
}