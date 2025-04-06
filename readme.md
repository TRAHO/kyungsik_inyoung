# PhotoSwipe 타일 기반 딥 줌 플러그인

**[> 플러그인 데모 <](https://dimsemenov.github.io/photoswipe-deep-zoom-plugin/)**

PhotoSwipe에 타일 기반 줌 기능을 추가하는 플러그인입니다. Deepzoom과 Zoomify 타일 형식을 지원합니다.

[OpenLayers](https://openlayers.org/en/latest/examples/zoomify.html)나 [OpenSeaDragon](https://openseadragon.github.io/)과 같은 일반적인 뷰어와 달리, 사용자가 기본 이미지 크기 이상으로 확대할 때만 타일을 표시합니다. 사용자가 확대하지 않으면 PhotoSwipe는 평소처럼 작동합니다.

단일 타일 이미지만 표시하려는 경우, OpenLayers, OpenSeaDragon 또는 Leaflet을 사용하는 것을 고려해보세요.

### 초기화

플러그인은 단일 JS 파일 [photoswipe-deep-zoom-plugin.esm.js](photoswipe-deep-zoom-plugin.esm.js)가 필요합니다. 소스 코드를 보려면 [src/](src/)를 방문하세요.

또는 NPM을 통해 설치할 수 있습니다: `npm install photoswipe-deep-zoom-plugin`.

```js
import PhotoSwipeLightbox from './lib/photoswipe/photoswipe-lightbox.esm.js';
import PhotoSwipe from './lib/photoswipe/photoswipe.esm.js';
import PhotoSwipeDeepZoom from './photoswipe-deep-zoom-plugin.esm.js';

const lightbox = new PhotoSwipeLightbox({
  gallery: '#gallery',
  children: '.pswp-gallery__item',
  pswpModule: PhotoSwipe,

  // 이 플러그인을 위한 권장 PhotoSwipe 옵션
  allowPanToNext: false, // 이미지가 확대되었을 때 다음 슬라이드로 스와이프하는 것을 방지
  allowMouseDrag: true, // 최대 확대 수준에서 드래그 커서 표시
  wheelToZoom: true, // 휠 기반 확대 활성화
  zoom: false // 기본 확대 버튼 비활성화
});

const deepZoomPlugin = new PhotoSwipeDeepZoom(lightbox, {
  // 딥 줌 플러그인 옵션, 예시:
  tileSize: 256
});

lightbox.init();
```

단일 슬라이드에 대한 HTML:

```html
<div id="gallery" class="pswp-gallery">
  <a  
    class="pswp-gallery__item"
    href="primary-image.jpg"
    target="_blank"
    data-pswp-width="1600"
    data-pswp-height="1024"
    data-pswp-tile-type="deepzoom"
    data-pswp-tile-url="path/to/tiles/{z}/{x}_{y}.jpeg"
    data-pswp-max-width="5832"
    data-pswp-max-height="4409">
    <img src="thumbnail.jpg" alt="Image #1" />
  </a>
  <!-- 더 많은 슬라이드 ... -->
</div>
```

또는 슬라이드 데이터로:

```js
const slides = [
  {
    src: 'primary-image.jpg',
    w: 1600,
    h: 1024,
    msrc: 'thumbnail.jpg',
    tileType: 'deepzoom',
    tileUrl: 'path/to/tiles/{z}/{x}_{y}.jpeg',
    tileSize: 254,
    tileOverlap: 1,
    maxWidth: 5832,
    maxHeight: 4409
  },
  // 더 많은 슬라이드...
];
```

#### `data-pswp-tile-url`

타일 URL. 슬라이드 데이터의 `tileUrl` 속성으로도 정의할 수 있습니다.

예시: `https://example.com/images/my-image/{z}/{x}_{y}.jpg`. `{z}`, `{x}`, `{y}`는 자동으로 해당 타일 좌표로 대체됩니다.

Zoomify 타입을 사용하는 경우, 추가 템플릿 문자열 `{zoomify_group}`이 있습니다. 예시:

```
https://example.com/images/my-image/TileGroup{zoomify_group}/{z}-{x}-{y}.jpg"
```

#### `data-pswp-tile-type: 'deepzoom'`

현재 `deepzoom`과 `zoomify` 타일 유형이 지원됩니다. 슬라이드 데이터의 `tileType` 속성으로도 정의할 수 있습니다.

#### `data-pswp-max-width` 및 `data-pswp-max-height`

타일 이미지의 최대 크기.

#### `data-pswp-max-zoom-width`

선택적. 이미지를 얼마나 확대할 수 있는지 제어하며, 최대 이미지 너비보다 낮거나 높을 수 있습니다.

### 타일 소스

현재 플러그인은 Zoomify와 Deepzoom 타일만 지원합니다. [Vips](https://www.libvips.org/API/current/Making-image-pyramids.md.html)를 사용하여 이를 생성할 수 있습니다.

### 옵션

#### `fadeInDuration: 150`

타일이 로드될 때의 페이드 인 애니메이션 지속 시간, 0일 수 있습니다.

#### `cacheLimit: 200`

캐시에 유지할 타일 수.

#### `maxDecodingCount: 15`

동시에 최대 디코딩 요청 수.

#### `minBatchRequestCount: 6`

`maxDecodingCount`에 도달했을 때 요청을 함께 일괄 처리하여 한 번에 하나씩 전송되지 않도록 합니다.

#### `tileSize: 256`

기본 타일 너비. 개별 슬라이드는 `data-pswp-tile-size` 속성이나 슬라이드 데이터의 `tileSize` 속성을 통해 이를 재정의할 수 있습니다.

#### `tileOverlap: 0`

기본 타일 오버랩. 개별 슬라이드는 `data-pswp-tile-overlap` 속성이나 슬라이드 데이터의 `tileOverlap` 속성을 통해 이를 재정의할 수 있습니다.

#### `incrementalZoomButtons: true`

툴바의 확대 및 축소 버튼.

#### `useLowResLayer: false`

활성 레이어 아래에 저해상도 레이어를 영구적으로 표시합니다.

#### `forceWillChange: true`

플레이스홀더와 기본 PhotoSwipe 이미지에 `will-change:transform`을 적용합니다.

#### `getTileUrlFn`

개별 타일 URL을 반환해야 하는 함수. 예시:

```
getTileUrlFn: (slideData, x, y, z) {
  return slideData.tileUrl
        .replace('{x}', x)
        .replace('{y}', y)
        .replace('{z}', z);
}
```

#### `maxTilePixelRatio: 1`

뷰어는 고해상도 화면에서 더 높은 해상도의 타일을 더 일찍 로드합니다.

예를 들어, 장치 픽셀 비율이 `2`(일반 레티나 화면)이고 `maxTilePixelRatio: 2`인 경우, 뷰어는 두 배 더 많은 타일을 렌더링합니다.

장치 픽셀 비율이 `maxTilePixelRatio`보다 높으면, 뷰어는 `maxTilePixelRatio` 옵션에 따라 타일을 렌더링합니다. 낮으면 장치 픽셀 비율에 따라 렌더링합니다.

### 빌드

`dist/`에서 JS를 빌드하고 최소화하려면:

```
npm run build
```

GitHub 페이지를 업데이트하려면:

```
npm run publish:demo
```

### 변경 로그

#### v1.1

- 고해상도 화면 지원, `maxTilePixelRatio` 옵션 추가.
- 슬라이드 확대 정도를 늘리거나 줄일 수 있는 `pswp-max-zoom-width`(`pswpMaxZoomWidth`) 속성 추가.
- 플러그인이 이제 로딩 표시기의 동작을 조정하며, 타일이 로드될 때 표시됩니다.
- 확대 키보드 단축키(`+` 및 `-`) 추가.
- 초기 상태의 x3 이상 확대되었을 때 표시되는 확대 초기화 버튼 추가. 