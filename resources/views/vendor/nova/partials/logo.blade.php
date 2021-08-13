{{-- <svg
    class="fill-current"
    width="{{ $width ?? '126' }}"
    height="{{ $height ?? '24' }}"
    viewBox="{{ $viewBox ?? '0 0 126 24' }}"
    xmlns="http://www.w3.org/2000/svg"
>
    <path d="M40.76 18h-6.8V7.328h2.288V16h4.512v2zm8.064 0h-2.048v-.816c-.528.64-1.44 1.008-2.448 1.008-1.232 0-2.672-.832-2.672-2.56 0-1.824 1.44-2.496 2.672-2.496 1.04 0 1.936.336 2.448.944v-.976c0-.784-.672-1.296-1.696-1.296-.816 0-1.584.32-2.224.912l-.8-1.424c.944-.848 2.16-1.216 3.376-1.216 1.776 0 3.392.704 3.392 2.928V18zm-3.68-1.184c.656 0 1.296-.224 1.632-.672v-.96c-.336-.448-.976-.688-1.632-.688-.8 0-1.456.432-1.456 1.168s.656 1.152 1.456 1.152zM52.856 18h-2.032v-7.728h2.032v1.04c.56-.672 1.504-1.232 2.464-1.232v1.984a2.595 2.595 0 0 0-.56-.048c-.672 0-1.568.384-1.904.88V18zm10.416 0h-2.048v-.816c-.528.64-1.44 1.008-2.448 1.008-1.232 0-2.672-.832-2.672-2.56 0-1.824 1.44-2.496 2.672-2.496 1.04 0 1.936.336 2.448.944v-.976c0-.784-.672-1.296-1.696-1.296-.816 0-1.584.32-2.224.912l-.8-1.424c.944-.848 2.16-1.216 3.376-1.216 1.776 0 3.392.704 3.392 2.928V18zm-3.68-1.184c.656 0 1.296-.224 1.632-.672v-.96c-.336-.448-.976-.688-1.632-.688-.8 0-1.456.432-1.456 1.168s.656 1.152 1.456 1.152zM69.464 18h-2.192l-3.104-7.728h2.176l2.016 5.376 2.032-5.376h2.176L69.464 18zm7.648.192c-2.352 0-4.128-1.584-4.128-4.064 0-2.24 1.664-4.048 4-4.048 2.32 0 3.872 1.728 3.872 4.24v.48h-5.744c.144.944.912 1.728 2.224 1.728.656 0 1.552-.272 2.048-.752l.912 1.344c-.768.704-1.984 1.072-3.184 1.072zm1.792-4.8c-.064-.736-.576-1.648-1.92-1.648-1.264 0-1.808.88-1.888 1.648h3.808zM84.36 18h-2.032V7.328h2.032V18zm15.232 0h-1.28l-6.224-8.512V18H90.76V7.328h1.36l6.144 8.336V7.328h1.328V18zm5.824.192c-2.352 0-3.824-1.824-3.824-4.064s1.472-4.048 3.824-4.048 3.824 1.808 3.824 4.048-1.472 4.064-3.824 4.064zm0-1.072c1.648 0 2.56-1.408 2.56-2.992 0-1.568-.912-2.976-2.56-2.976-1.648 0-2.56 1.408-2.56 2.976 0 1.584.912 2.992 2.56 2.992zm9.152.88h-1.312l-3.216-7.728h1.312l2.56 6.336 2.576-6.336h1.296L114.568 18zm10.496 0h-1.2v-.88c-.624.704-1.52 1.072-2.56 1.072-1.296 0-2.688-.88-2.688-2.56 0-1.744 1.376-2.544 2.688-2.544 1.056 0 1.936.336 2.56 1.04v-1.392c0-1.024-.832-1.616-1.952-1.616-.928 0-1.68.32-2.368 1.072l-.56-.832c.832-.864 1.824-1.28 3.088-1.28 1.648 0 2.992.736 2.992 2.608V18zm-3.312-.672c.832 0 1.648-.32 2.112-.96v-1.472c-.464-.624-1.28-.944-2.112-.944-1.136 0-1.92.704-1.92 1.68 0 .992.784 1.696 1.92 1.696zM20.119 20.455A12.184 12.184 0 0 1 11.5 24a12.18 12.18 0 0 1-9.333-4.319c4.772 3.933 11.88 3.687 16.36-.738a7.571 7.571 0 0 0 0-10.8c-3.018-2.982-7.912-2.982-10.931 0a3.245 3.245 0 0 0 0 4.628 3.342 3.342 0 0 0 4.685 0 1.114 1.114 0 0 1 1.561 0 1.082 1.082 0 0 1 0 1.543 5.57 5.57 0 0 1-7.808 0 5.408 5.408 0 0 1 0-7.714c3.881-3.834 10.174-3.834 14.055 0a9.734 9.734 0 0 1 .03 13.855zm.714-16.136C16.06.386 8.953.632 4.473 5.057a7.571 7.571 0 0 0 0 10.8c3.018 2.982 7.912 2.982 10.931 0a3.245 3.245 0 0 0 0-4.628 3.342 3.342 0 0 0-4.685 0 1.114 1.114 0 0 1-1.561 0 1.082 1.082 0 0 1 0-1.543 5.57 5.57 0 0 1 7.808 0 5.408 5.408 0 0 1 0 7.714c-3.881 3.834-10.174 3.834-14.055 0a9.734 9.734 0 0 1-.015-13.87C5.096 1.35 8.138 0 11.5 0c3.75 0 7.105 1.68 9.333 4.319z" fill-rule="evenodd"/>
</svg> --}}


<svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-primary" width="{{ $width ?? '150' }}"
    height="{{ $height ?? '30' }}" viewBox="0 0 223.2 37.4">
    <path id="Union_1" data-name="Union 1"
        d="M112.86,46.82a1.815,1.815,0,0,1-.7-1.46,2.58,2.58,0,0,1,.24-1l2.72-5.72-7.44-15.48a2.031,2.031,0,0,1-.2-.92,1.95,1.95,0,0,1,.78-1.56,2.729,2.729,0,0,1,1.78-.64,2.347,2.347,0,0,1,1.239.339,2.27,2.27,0,0,1,.88,1.06l5.6,12.361,5.56-12.32a2.314,2.314,0,0,1,.86-1.06,2.171,2.171,0,0,1,1.18-.34,2.52,2.52,0,0,1,1.66.62,1.932,1.932,0,0,1,.741,1.54,2.053,2.053,0,0,1-.24.92L116.6,46.04a2.235,2.235,0,0,1-2.12,1.36A2.463,2.463,0,0,1,112.86,46.82ZM15.4,43.6V31A1.4,1.4,0,0,0,14,29.6H7A1.4,1.4,0,0,0,5.6,31V43.6H2.8a1.4,1.4,0,0,1-1.4-1.4V26.555a4.169,4.169,0,0,0,1.4.246H7a4.183,4.183,0,0,0,2.8-1.073A4.182,4.182,0,0,0,12.6,26.8H21a4.182,4.182,0,0,0,2.8-1.073A4.186,4.186,0,0,0,26.6,26.8h4.2a4.174,4.174,0,0,0,1.4-.246V42.2a1.4,1.4,0,0,1-1.4,1.4ZM18.2,31v4.2a1.4,1.4,0,0,0,1.4,1.4h7A1.4,1.4,0,0,0,28,35.2V31a1.4,1.4,0,0,0-1.4-1.4h-7A1.4,1.4,0,0,0,18.2,31Zm118.34,8.42A11.738,11.738,0,0,1,132.16,37a1.836,1.836,0,0,1-.6-1.4,1.571,1.571,0,0,1,.32-.98.938.938,0,0,1,.76-.42,1.932,1.932,0,0,1,1.12.44,13.484,13.484,0,0,0,8.48,2.88,8.974,8.974,0,0,0,5.28-1.32,4.351,4.351,0,0,0,1.84-3.76,3.017,3.017,0,0,0-.88-2.26,6.348,6.348,0,0,0-2.32-1.38,34.946,34.946,0,0,0-3.88-1.08,37.852,37.852,0,0,1-5.38-1.6,8.382,8.382,0,0,1-3.4-2.4,6.164,6.164,0,0,1-1.3-4.081,7.252,7.252,0,0,1,1.3-4.26,8.409,8.409,0,0,1,3.64-2.88,13.237,13.237,0,0,1,5.34-1.02,15.478,15.478,0,0,1,5.22.859,10.984,10.984,0,0,1,4.06,2.46,1.952,1.952,0,0,1,.64,1.4,1.571,1.571,0,0,1-.32.98.938.938,0,0,1-.76.42,2.439,2.439,0,0,1-1.16-.44,13.571,13.571,0,0,0-3.64-2.24,11.367,11.367,0,0,0-4.04-.64,8.451,8.451,0,0,0-5.16,1.38,4.549,4.549,0,0,0-1.84,3.86,3.547,3.547,0,0,0,1.62,3.18,16.358,16.358,0,0,0,4.981,1.86q3.64.88,5.719,1.62a8.561,8.561,0,0,1,3.46,2.22,5.574,5.574,0,0,1,1.38,3.96,6.927,6.927,0,0,1-1.3,4.16,8.452,8.452,0,0,1-3.66,2.8,13.728,13.728,0,0,1-5.4,1A18.27,18.27,0,0,1,136.54,39.42ZM90.6,38.6a2.387,2.387,0,0,1-1.281-2.16,1.9,1.9,0,0,1,.44-1.3,1.385,1.385,0,0,1,1.08-.5,4.419,4.419,0,0,1,1.88.64,17.559,17.559,0,0,0,2.34.92,9.528,9.528,0,0,0,2.7.32,5.14,5.14,0,0,0,2.62-.56,1.735,1.735,0,0,0,.941-1.561,1.507,1.507,0,0,0-.38-1.08,3.5,3.5,0,0,0-1.361-.74,28.535,28.535,0,0,0-2.98-.78,11.448,11.448,0,0,1-5.1-2.039,4.54,4.54,0,0,1-1.54-3.641A5.255,5.255,0,0,1,91,22.94a6.741,6.741,0,0,1,2.86-2.2A10.43,10.43,0,0,1,98,19.96a12.417,12.417,0,0,1,3.24.419,9.873,9.873,0,0,1,2.8,1.22,2.415,2.415,0,0,1,1.28,2.12,2.051,2.051,0,0,1-.44,1.339,1.344,1.344,0,0,1-1.08.54,2.15,2.15,0,0,1-.86-.18,11.393,11.393,0,0,1-1.06-.54,16.949,16.949,0,0,0-2-.9,6.244,6.244,0,0,0-2.08-.3,4.022,4.022,0,0,0-2.34.6,1.9,1.9,0,0,0-.86,1.64,1.664,1.664,0,0,0,.88,1.52,12.606,12.606,0,0,0,3.4,1.039,19.367,19.367,0,0,1,4.2,1.281,4.919,4.919,0,0,1,2.181,1.8,5.228,5.228,0,0,1,.66,2.759,5.13,5.13,0,0,1-2.26,4.361,10.032,10.032,0,0,1-6.02,1.64A12.859,12.859,0,0,1,90.6,38.6Zm-18.96.92a6.326,6.326,0,0,1-2.52-2.2,5.563,5.563,0,0,1-.92-3.12,4.943,4.943,0,0,1,1.1-3.4A6.616,6.616,0,0,1,72.88,29a32.59,32.59,0,0,1,6.84-.56h1v-.92a4.1,4.1,0,0,0-.84-2.86,3.575,3.575,0,0,0-2.721-.9,8.627,8.627,0,0,0-2.36.34q-1.2.34-2.84.98a4,4,0,0,1-1.52.519,1.5,1.5,0,0,1-1.18-.519,1.977,1.977,0,0,1-.461-1.36,2.05,2.05,0,0,1,.34-1.181,3.23,3.23,0,0,1,1.14-.94,13.039,13.039,0,0,1,3.34-1.2,17.23,17.23,0,0,1,3.82-.44q4.081,0,6.061,2.02t1.98,6.14v9.76a2.257,2.257,0,0,1-.64,1.7,2.424,2.424,0,0,1-1.76.62,2.341,2.341,0,0,1-1.7-.64,2.236,2.236,0,0,1-.66-1.68V37a5.217,5.217,0,0,1-2.14,2.44,6.415,6.415,0,0,1-3.38.88A7.844,7.844,0,0,1,71.64,39.52Zm4.2-8.2a4.043,4.043,0,0,0-2.12.84,2.141,2.141,0,0,0-.64,1.641,2.713,2.713,0,0,0,.9,2.1,3.177,3.177,0,0,0,2.22.82,4.271,4.271,0,0,0,3.26-1.34,4.847,4.847,0,0,0,1.26-3.46V31.08H80A27.556,27.556,0,0,0,75.84,31.32Zm132.5,6.28a10.132,10.132,0,0,1-2.62-7.4,11.6,11.6,0,0,1,1.16-5.3,8.648,8.648,0,0,1,3.26-3.58,9,9,0,0,1,4.78-1.28,7.734,7.734,0,0,1,6.04,2.479,9.652,9.652,0,0,1,2.24,6.72,1.725,1.725,0,0,1-.32,1.16,1.33,1.33,0,0,1-1.04.36H208.96q.359,6.88,6.52,6.88a7.646,7.646,0,0,0,2.68-.419,17.534,17.534,0,0,0,2.4-1.14,3.8,3.8,0,0,1,1.4-.6.988.988,0,0,1,.76.36,1.334,1.334,0,0,1,.32.92q0,1-1.4,1.84a12.121,12.121,0,0,1-3.04,1.26,12.27,12.27,0,0,1-3.12.42A9.527,9.527,0,0,1,208.34,37.6Zm2.52-13.441a7.311,7.311,0,0,0-1.82,4.521H220.36a6.929,6.929,0,0,0-1.48-4.54,4.965,4.965,0,0,0-3.92-1.58A5.427,5.427,0,0,0,210.86,24.16ZM174.18,39.04a8.272,8.272,0,0,1-3.24-3.541,11.882,11.882,0,0,1-1.139-5.34,11.882,11.882,0,0,1,1.139-5.34,8.272,8.272,0,0,1,3.24-3.541,9.384,9.384,0,0,1,4.86-1.239,9.493,9.493,0,0,1,4.881,1.239,8.233,8.233,0,0,1,3.26,3.541,11.882,11.882,0,0,1,1.139,5.34,11.882,11.882,0,0,1-1.139,5.34,8.233,8.233,0,0,1-3.26,3.541,9.493,9.493,0,0,1-4.881,1.24A9.385,9.385,0,0,1,174.18,39.04Zm.46-14.38a8.579,8.579,0,0,0-1.56,5.5,8.637,8.637,0,0,0,1.54,5.52,6.069,6.069,0,0,0,8.84.021A8.627,8.627,0,0,0,185,30.16a8.574,8.574,0,0,0-1.561-5.5,5.959,5.959,0,0,0-8.8,0ZM165.76,40.2l-1.08-.08A6.785,6.785,0,0,1,159.96,38.2a7.365,7.365,0,0,1-1.52-5.08V23.08H155.76a1.533,1.533,0,0,1-1.06-.339,1.149,1.149,0,0,1-.38-.9,1.259,1.259,0,0,1,.38-.96,1.478,1.478,0,0,1,1.06-.36h2.679V16a1.553,1.553,0,0,1,.44-1.18,1.668,1.668,0,0,1,1.2-.42,1.612,1.612,0,0,1,1.16.42A1.556,1.556,0,0,1,161.68,16v4.52h4.441a1.42,1.42,0,0,1,1.02.36,1.259,1.259,0,0,1,.38.96,1.149,1.149,0,0,1-.38.9,1.471,1.471,0,0,1-1.02.339H161.68v10.2a4.627,4.627,0,0,0,.9,3.18,3.858,3.858,0,0,0,2.7,1.1l1.08.08q1.4.12,1.4,1.28a1.151,1.151,0,0,1-.5,1.021,2.006,2.006,0,0,1-1.137.275Q165.95,40.215,165.76,40.2Zm27.859-.42a1.519,1.519,0,0,1-.46-1.18V21.72a1.483,1.483,0,0,1,.481-1.18,1.7,1.7,0,0,1,1.159-.419,1.473,1.473,0,0,1,1.52,1.56v2.24a6.074,6.074,0,0,1,2.381-2.68,8.063,8.063,0,0,1,3.54-1.08l.56-.04q1.681-.12,1.68,1.36a1.42,1.42,0,0,1-.36,1.02,1.915,1.915,0,0,1-1.24.46l-1.2.12a5.394,5.394,0,0,0-3.98,1.9,6.139,6.139,0,0,0-1.3,3.9V38.6a1.541,1.541,0,0,1-.44,1.2,1.655,1.655,0,0,1-1.16.4A1.684,1.684,0,0,1,193.62,39.78ZM48.32,40a2.5,2.5,0,0,1-1.82-.64,2.39,2.39,0,0,1-.661-1.8V14.24a2.391,2.391,0,0,1,.661-1.8,2.5,2.5,0,0,1,1.82-.64H62.76a2.783,2.783,0,0,1,1.8.52,1.8,1.8,0,0,1,.64,1.48,1.9,1.9,0,0,1-.64,1.54,2.71,2.71,0,0,1-1.8.54H50.88V23.64H61.96a2.783,2.783,0,0,1,1.8.519,1.844,1.844,0,0,1,.641,1.52,1.8,1.8,0,0,1-.641,1.48,2.783,2.783,0,0,1-1.8.52H50.88V35.92H62.76a2.71,2.71,0,0,1,1.8.54A1.9,1.9,0,0,1,65.2,38a1.8,1.8,0,0,1-.64,1.48,2.783,2.783,0,0,1-1.8.519ZM26.6,25.4a2.8,2.8,0,0,1-2.8-2.8A2.8,2.8,0,0,1,21,25.4H12.6a2.8,2.8,0,0,1-2.8-2.8A2.8,2.8,0,0,1,7,25.4H2.8A2.8,2.8,0,0,1,0,22.6V21.2H33.6v1.4a2.8,2.8,0,0,1-2.8,2.8Zm-1.652-5.6L22.988,10H29.4a1.4,1.4,0,0,1,1.345,1.015l2.51,8.785Zm-13.441,0,1.96-9.8h6.665l1.96,9.8ZM.344,19.8l2.51-8.785A1.4,1.4,0,0,1,4.2,10h6.412L8.653,19.8Z"
        transform="translate(0 -10)" fill-rule="evenodd" />
</svg>
