// $(function () {
    // const cameraSize = { w: 360, h: 240 };
    // const canvasSize = { w: 360, h: 240 };
    // const resolution = { w: 1080, h: 720 };
    // let video;
    // let media;
    // let canvas;
    // let canvasCtx;

    // // video要素をつくる
    // video = document.createElement('video');
    // video.id = 'video';
    // video.width = cameraSize.w;
    // video.height = cameraSize.h;
    // video.autoplay = true;
    // document.getElementById('videoPreview').appendChild(video);

    // // video要素にWebカメラの映像を表示させる
    // media = navigator.mediaDevices.getUserMedia({
    //     audio: false,
    //     video: {
    //         width: { ideal: resolution.w },
    //         height: { ideal: resolution.h }
    //     }
    // }).then(function (stream) {
    //     video.srcObject = stream;
    // });

    // // canvas要素をつくる
    // canvas = document.createElement('canvas');
    // canvas.id = 'canvas';
    // canvas.width = canvasSize.w;
    // canvas.height = canvasSize.h;
    // document.getElementById('canvasPreview').appendChild(canvas);

    // // コンテキストを取得する
    // canvasCtx = canvas.getContext('2d');

    // // video要素の映像をcanvasに描画する
    // canvasUpdate();
    // faceRecog();

    // function canvasUpdate() {
    //     canvasCtx.drawImage(video, 0, 0, canvas.width, canvas.height);
    //     requestAnimationFrame(canvasUpdate);
    // };



    // function faceRecog() {
    //     let src = cv.imread('canvasCtx');
    //     let gray = new cv.Mat();
    //     cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY, 0);
    //     let faces = new cv.RectVector();
    //     let eyes = new cv.RectVector();
    //     let faceCascade = new cv.CascadeClassifier();
    //     let eyeCascade = new cv.CascadeClassifier();
    //     faceCascade.load('haarcascade_frontalface_default.xml');
    //     eyeCascade.load('haarcascade_eye.xml');
    //     let msize = new cv.Size(0, 0);
    //     faceCascade.detectMultiScale(gray, faces, 1.1, 3, 0, msize, msize);


    //     for (let i = 0; i < faces.size(); ++i) {
    //         let roiGray = gray.roi(faces.get(i));
    //         let roiSrc = src.roi(faces.get(i));
    //         let point1 = new cv.Point(faces.get(i).x, faces.get(i).y);
    //         let point2 = new cv.Point(faces.get(i).x + faces.get(i).width, faces.get(i).y + faces.get(i).height);
    //         cv.rectangle(src, point1, point2, [255, 0, 0, 255]);
    //         eyeCascade.detectMultiScale(roiGray, eyes);
    //         for (let j = 0; j < eyes.size(); ++j) {
    //             let point1 = new cv.Point(eyes.get(j).x, eyes.get(j).y);
    //             let point2 = new cv.Point(eyes.get(j).x + eyes.get(j).width, eyes.get(j).y + eyes.get(j).height);
    //             cv.rectangle(roiSrc, point1, point2, [0, 0, 255, 255]);
    //         }
    //         roiGray.ïelete();
    //         roiSrc.delete();
    //     }
    //     cv.imshow('canvasOutput', src);
    //     src.delete();
    //     gray.delete();
    //     faceCascade.delete();
    //     eyeCascade.delete();
    //     faces.delete();
    //     eyes.delete();
    // }
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

                }, 500);

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
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then((stream) => {

                        this.video.srcObject = stream;
                        this.video.play();

                    });

            });

        }
    });

