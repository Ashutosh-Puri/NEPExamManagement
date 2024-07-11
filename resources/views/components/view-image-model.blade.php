@props(['title'=>"View"])
<div>
  <div id="view-document-model" tabindex="-1" class="modal fixed inset-0 items-center justify-center z-50 flex hidden w-full p-4 overflow-y-auto md:inset-0 min-h-full max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
          <h3 id="modalTitle" class="text-xl font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
          <button onclick="open_in_new_window()" type="button" class="hover:bg-blue-800 bg-blue-500 rounded-lg text-sm mx-1 w-8 h-8 ms-auto inline-flex justify-center items-center float-end text-white" data-modal-hide="large-modal">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <button onclick="closeModal()" type="button" class="hover:bg-red-800 bg-red-500 rounded-lg text-sm mx-1 w-8 h-8 inline-flex justify-center items-center text-white" data-modal-hide="large-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <div class="p-4 md:p-5 space-y-4">
            <div id="image-section" class="overflow-y-scroll" style="height: 500px;">
                <img id="view-document-model-image" class="border w-full max-h-50 object-cover" src="" alt="image description">
            </div>
          <embed id="view-document-model-pdf" type="application/pdf" class="border w-full object-cover overflow-y-scroll" height="500px" src="" alt="pdf description" />
        </div>
      </div>
    </div>
  </div>
  @section('scripts')
    <script>
      var path;
      function openModal(documentPath ,title) {
        path=documentPath;
        const fileExtension = documentPath.match(/\.([^.]+)$/);
        if (fileExtension && fileExtension[1] === 'pdf') {
          document.getElementById("view-document-model-pdf").style.display = "block";
          document.getElementById("view-document-model-pdf").src = documentPath;
          document.getElementById("image-section").style.display = "none";
        } else {
          document.getElementById("image-section").style.display = "block";
          document.getElementById("view-document-model-image").src = documentPath;
          document.getElementById("view-document-model-pdf").style.display = "none";
        }
        document.getElementById("view-document-model").classList.remove("hidden");
        document.getElementById("modalTitle").textContent = title;
      }

      function closeModal() {
        document.getElementById("view-document-model").classList.add("hidden");
      }

      function open_in_new_window() {
        if(path)
        {
          window.open(path, '_blank');

        }
      }
    </script>
  @endsection
</div>
