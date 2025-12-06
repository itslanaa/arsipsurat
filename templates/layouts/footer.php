        <!-- Akhir dari konten dinamis -->
    </div>
</main>
<!-- Akhir dari Main Content Wrapper -->

</div> <!-- Akhir dari flex h-screen -->


<!-- === POPUP / MODAL CONTAINER === -->
<div id="popup-container" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full z-50 flex items-center justify-center transition-opacity duration-300 opacity-0">
    <!-- Modal Content -->
    <div id="popup-modal" class="relative mx-auto p-6 border w-full max-w-sm shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95">
        <!-- Success Icon -->
        <div id="popup-icon-success" class="hidden w-20 h-20 mx-auto -mt-16 bg-green-500 border-4 border-white rounded-full flex items-center justify-center">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
        <!-- Error Icon -->
        <div id="popup-icon-error" class="hidden w-20 h-20 mx-auto -mt-16 bg-red-500 border-4 border-white rounded-full flex items-center justify-center">
             <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="crossmark__check" fill="none" d="M16 16 36 36 M36 16 16 36"/>
            </svg>
        </div>
         <!-- Confirmation/Info Icon -->
        <div id="popup-icon-confirm" class="hidden w-20 h-20 mx-auto -mt-16 bg-blue-500 border-4 border-white rounded-full flex items-center justify-center">
            <i class="fas fa-info-circle text-4xl text-white"></i>
        </div>
        <!-- Confirmation Icon (Danger) -->
        <div id="popup-icon-danger" class="hidden w-20 h-20 mx-auto -mt-16 bg-red-500 border-4 border-white rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-4xl text-white"></i>
        </div>
        
        <div class="mt-3 text-center">
            <h3 id="popup-title" class="text-2xl leading-6 font-bold text-gray-900"></h3>
            <div class="mt-4 px-7 py-3">
                <p id="popup-message" class="text-base text-gray-600"></p>
            </div>
            <div id="popup-buttons" class="items-center px-4 py-3 space-x-4">
                <!-- Buttons will be injected here by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- CSS for Animated Icons -->
<style>
.checkmark__circle { stroke-dasharray: 166; stroke-dashoffset: 166; stroke-width: 2; stroke-miterlimit: 10; stroke: #fff; fill: none; animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards; }
.checkmark { width: 56px; height: 56px; border-radius: 50%; display: block; stroke-width: 2; stroke: #fff; stroke-miterlimit: 10; margin: 10% auto; box-shadow: inset 0px 0px 0px #4bb71b; animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both; }
.checkmark__check { transform-origin: 50% 50%; stroke-dasharray: 48; stroke-dashoffset: 48; animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards; }
@keyframes stroke { 100% { stroke-dashoffset: 0; } }
@keyframes scale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }
@keyframes fill { 100% { box-shadow: inset 0px 0px 0px 30px #4bb71b; } }

.crossmark__circle { stroke-dasharray: 166; stroke-dashoffset: 166; stroke-width: 2; stroke-miterlimit: 10; stroke: #f87171; fill: none; animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards; }
.crossmark { width: 56px; height: 56px; border-radius: 50%; display: block; stroke-width: 2; stroke: #fff; stroke-miterlimit: 10; margin: 10% auto; animation: scale .3s ease-in-out .9s both; }
.crossmark__check { transform-origin: 50% 50%; stroke-dasharray: 48; stroke-dashoffset: 48; animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards; }
</style>

<!-- JavaScript Libraries -->
<script src="<?= BASE_URL; ?>/assets/js/chart.js"></script> 

<!-- Custom JavaScript Utama -->
<script src="<?= BASE_URL; ?>/assets/js/script.js"></script>

<!-- === JAVASCRIPT BARU UNTUK DROPDOWN & POPUP === -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown Logic
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
        window.addEventListener('click', () => {
            if (!userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
            }
        });
    }

    // Popup Logic
    const popupContainer = document.getElementById('popup-container');
    const popupModal = document.getElementById('popup-modal');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    const popupButtons = document.getElementById('popup-buttons');
    const iconSuccess = document.getElementById('popup-icon-success');
    const iconError = document.getElementById('popup-icon-error');
    const iconConfirm = document.getElementById('popup-icon-confirm');
    const iconDanger = document.getElementById('popup-icon-danger');

    function showPopup(title, message, type) {
        popupTitle.innerText = title;
        popupMessage.innerText = message;
        
        iconSuccess.classList.add('hidden');
        iconError.classList.add('hidden');
        iconConfirm.classList.add('hidden');
        iconDanger.classList.add('hidden');

        popupButtons.innerHTML = '';
        const okButton = document.createElement('button');
        okButton.innerText = 'Tutup';
        okButton.className = 'px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none';
        okButton.onclick = hidePopup;
        popupButtons.appendChild(okButton);

        if (type === 'success') {
            iconSuccess.classList.remove('hidden');
        } else if (type === 'error') {
            iconError.classList.remove('hidden');
        } else if (type === 'info') {
            iconConfirm.classList.remove('hidden'); // Menggunakan ikon info/confirm
        }
        
        popupContainer.classList.remove('hidden');
        setTimeout(() => {
            popupContainer.classList.remove('opacity-0');
            popupModal.classList.remove('scale-95');
        }, 10);
    }

    function showConfirmation(title, message, confirmUrl, confirmText = 'Ya, Hapus', confirmType = 'danger') {
        popupTitle.innerText = title;
        popupMessage.innerText = message;

        iconSuccess.classList.add('hidden');
        iconError.classList.add('hidden');
        iconConfirm.classList.add('hidden');
        iconDanger.classList.add('hidden');

        if (confirmType === 'danger') {
            iconDanger.classList.remove('hidden');
        } else {
            iconConfirm.classList.remove('hidden');
        }

        popupButtons.innerHTML = '';
        const cancelButton = document.createElement('button');
        cancelButton.innerText = 'Batal';
        cancelButton.className = 'px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none';
        cancelButton.onclick = hidePopup;
        
        const confirmButton = document.createElement('a');
        confirmButton.innerText = confirmText;
        confirmButton.href = confirmUrl;
        
        if (confirmType === 'danger') {
            confirmButton.className = 'px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none';
        } else {
            confirmButton.className = 'px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none';
        }

        popupButtons.appendChild(cancelButton);
        popupButtons.appendChild(confirmButton);

        popupContainer.classList.remove('hidden');
        setTimeout(() => {
            popupContainer.classList.remove('opacity-0');
            popupModal.classList.remove('scale-95');
        }, 10);
    }

    function hidePopup() {
        popupContainer.classList.add('opacity-0');
        popupModal.classList.add('scale-95');
        setTimeout(() => {
            popupContainer.classList.add('hidden');
        }, 300);
    }

    // Check for flash messages on page load
    const flashData = document.getElementById('flash-data');
    if (flashData) {
        const message = flashData.dataset.pesan;
        const action = flashData.dataset.aksi;
        const type = flashData.dataset.tipe;
        
        // === logic untuk menentukan judul ===
        let title;
        if (type === 'success') {
            title = 'Berhasil!';
        } else if (type === 'error') {
            title = 'Gagal!';
        } else { // 'info' atau tipe lainnya
            title = message; // Judul diambil dari pesan utama
        }
        
        const fullMessage = (type === 'info') ? action : `${message} ${action}.`;
        showPopup(title, fullMessage, type);
    }

    // Make functions globally accessible for inline onclick
    window.showConfirmation = showConfirmation;
});
</script>

</body>
</html>
