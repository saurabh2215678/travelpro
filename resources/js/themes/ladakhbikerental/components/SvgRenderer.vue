<template>
    <object :data="svgPath" type="image/svg+xml" @load="onLoad" ref="svgRef" />
  </template>
 <style scoped>
  object{width: 2px; height: 2px;}
 </style>
  <script>
  export default {
    name: 'SvgRenderer',
    props: {
      svgPath: {
        type: String,
        required: true,
      },
      preload: {
        type: Boolean,
        default: true,
      },
    },
    methods: {
        onLoad() {
            const svgRef = this.$refs.svgRef;

            if (svgRef && svgRef.contentDocument) {
            setTimeout(() => {
                const svgElement = svgRef.contentDocument.querySelector('svg');

                if (svgElement) {
                this.$refs.svgRef = svgElement;
                $(svgRef).replaceWith(svgElement);
                } else {
                console.error('Unable to find SVG element inside the loaded content.');
                }
            }, 100);
            } else {
            console.error('Unable to access contentDocument. SVG may not be fully loaded yet.');
            }
        },
    },

  };
  </script>
  