$(function() {
    
});

// テキストボックス
const textBox = document.getElementById("text-box")

// 背景画像
const bgImg = new Image()
bgImg.src = "bg-img.png"

// Canvas準備
const canvas = document.getElementById("cv")
const context = canvas.getContext("2d")

// 背景画像読込後にCanvasに描画
bgImg.onload = () => {
    context.drawImage(bgImg, 0, 0)
}

// 文字入力時に描画処理を呼び出す
textBox.addEventListener("input", () => {
    drawText(textBox.value)
})

// 描画処理
function drawText(text) {
    context.clearRect(0, 0, canvas.width, canvas.height)    // クリア
    context.drawImage(bgImg, 0, 0)                          // 背景画像描画
    context.fillStyle = 'black'                             // テキストカラーを指定
    context.font = "60px 'Kosugi Maru'"                     // フォントを指定
    context.textAlign = "center"                            // 左右中央
    context.textBaseline = "middle"                         // 上下中央
    context.fillText(text, 550, 250, 900);                  // テキスト描画
}

// シェアする処理
function share(){
    // Web Share APIの対応判定
    if (navigator.share !== undefined){
        // CanvasをBlobに変換→pngに変換
        canvas.toBlob( (blob) => {
            const shareImg = new File([blob], 'share.png', {type: 'image/png'})
            // シェア
            navigator.share({
                text: "トイプードルのひとこと",
                url: "https://web-breeze.net/add-text-to-image-and-share/",
                files: [shareImg]
            })
        })
    } else {
        alert("ご利用のブラウザがWeb Share APIに対応していません・・・")
    }
}