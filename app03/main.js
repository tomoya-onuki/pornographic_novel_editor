// $(function () {

//     // video要素をつくる
//     video = document.createElement('video');
//     video.id = 'video';
//     video.width = cameraSize.w;
//     video.height = cameraSize.h;
//     video.autoplay = true;


//     // 顔モデルをロード
//     const MODEL_URI = './face/weights';
//     Promise.all([
//         faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URI),
//         faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URI),
//         faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URI)
//     ])
//         .then(() => {
//             // ウェブカメラへアクセス
//             navigator.mediaDevices.getUserMedia({ video: true })
//                 .then((stream) => {
//                     video.srcObject = stream;
//                     video.play();
//                 });

//             onPlay(video);
//         });


//     function onPlay(video) {

//         setInterval(async () => {

//             // ウェブカメラの映像から顔データを取得
//             const detections = await faceapi.detectAllFaces(
//                 video,
//                 new faceapi.TinyFaceDetectorOptions()
//             )
//                 .withFaceLandmarks()
//                 .withFaceExpressions();

//             // 顔データをリサイズ
//             const resizedDetections = faceapi.resizeResults(detections, {
//                 width: video.width,
//                 height: video.height
//             });

//             // キャンバスに描画
//             canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
//             faceapi.draw.drawDetections(canvas, resizedDetections);
//             faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
//             faceapi.draw.drawFaceExpressions(canvas, resizedDetections)

//         }, 10);

//     }

// });


new Vue({
    el: '#app',
    data: {
        MODEL_URI: './face/weights',   // 顔認識モデルの場所
    },
    computed: {
        video() {

            return this.$refs['video'];

        },
        canvas() {

            return this.$refs['canvas'];

        }
    },
    methods: {
        onPlay() {

            const video = this.video;
            const canvas = this.canvas;

            setInterval(async () => {

                // ウェブカメラの映像から顔データを取得
                const detections = await faceapi.detectAllFaces(
                    this.video,
                    new faceapi.TinyFaceDetectorOptions()
                )
                    .withFaceLandmarks()
                    .withFaceExpressions();

                // 顔データをリサイズ
                const resizedDetections = faceapi.resizeResults(detections, {
                    width: video.width,
                    height: video.height
                });

                // キャンバスに描画
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                faceapi.draw.drawDetections(canvas, resizedDetections);
                faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                faceapi.draw.drawFaceExpressions(canvas, resizedDetections)

            }, 10);

        }
    },
    mounted() {

        // 顔モデルをロード
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(this.MODEL_URI),
            faceapi.nets.faceLandmark68Net.loadFromUri(this.MODEL_URI),
            faceapi.nets.faceExpressionNet.loadFromUri(this.MODEL_URI)
        ])
            .then(() => {

                // ウェブカメラへアクセス
                navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: false,
                }).then((stream) => {
                    this.video.srcObject = stream;
                    // this.video.play();
                });

            });
    }
});

