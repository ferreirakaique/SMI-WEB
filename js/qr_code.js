if('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices){
    alert('teste')
}

const videoStream = await navigator.mediaDevices.getUserMedia({ video: true })