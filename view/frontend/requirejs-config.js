var config = {
    map:{
        '*' : {
            "bxslider" : "Excellence_Slider/js/bxslider/jquery.bxslider",
            "flexslider" : "Excellence_Slider/js/flexslider/jquery.flexslider",
            "owlcarousel" : "Excellence_Slider/js/owlcarousel/owl.carousel",
            "unslider" : "Excellence_Slider/js/unslider/unslider-min",
            "customjs": "Excellence_Slider/js/slidersConfig"
        }
    },
    shim : {
        "bxslider" : {
            deps: ['jquery'],
            export : 'bxslider'
        },
        "flexslider" : {
            deps: ['jquery'],
            export : 'flexslider'
        },
        "owlcarousel" : {
            deps: ['jquery'],
            export : 'owlcarousel'
        },
        "unslider" : {
            deps: ['jquery'],
            export : 'unslider'
        }
    }
};