const tabManual = document.getElementById('tabManual');
const tabCSV = document.getElementById('tabCSV');
const formManual = document.getElementById('formManual');
const formCSV = document.getElementById('formCSV');

tabManual.addEventListener('click', () => {
    tabManual.classList.add('active');
    tabCSV.classList.remove('active');
    formManual.classList.remove('d-none');
    formCSV.classList.add('d-none');
});

tabCSV.addEventListener('click', () => {
    tabCSV.classList.add('active');
    tabManual.classList.remove('active');
    formCSV.classList.remove('d-none');
    formManual.classList.add('d-none');
});


const fileInput = document.getElementById('file');
const fileName = document.getElementById('file-name');
const dropZone = document.querySelector('.custom-file-upload');

fileInput.addEventListener('change', () => {
    fileName.textContent = fileInput.files[0]?.name || 'Pilih file atau seret ke sini';
});

// drag & drop (opsional biar lebih keren)
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    fileName.textContent = e.dataTransfer.files[0]?.name || 'Pilih file atau seret ke sini';
});

