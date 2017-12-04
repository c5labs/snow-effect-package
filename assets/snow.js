$(function() {
    //a point in 3d space
    function Particle (opts){
        $.extend(this, {
            x:0,
            y:0,
            z:0
        }, opts );
    }

    //get particle coordinates in 3d space from screen coordinates
    Particle.prototype.fromscreen = function(p, focalpoint){
        if(p.z !== this.z || !this.scalar){
            this.scalar = -focalpoint.z / (-focalpoint.z + p.z);
        }
        this.x = -Math.round((-focalpoint.x + p.x) * this.scalar);
        this.y = -Math.round((-focalpoint.y + p.y) * this.scalar);
        this.z = p.z;
        this.s = this.scalar;
    };

    //get screen coordinates from 3d space coordinates
    Particle.prototype.toscreen = function(focalpoint){
        if(!this.scalar){
            this.scalar = -focalpoint.z / (-focalpoint.z + this.z);
        }
        return {
            x: Math.round(focalpoint.x - (this.x/this.scalar)),
            y: Math.round(focalpoint.y - (this.y/this.scalar)),
            z: this.z,
            s: this.scalar
        };
    };

    function Screen ($el, opts){
        var  s = this;
        s.$el = $el;
        s.canvas = s.$el.is('canvas');
        s.opts = $.extend(s, {
            minopacity: 0.4,
            color: '#fff',
            focalpoint: {
                x:s.$el[0].width/2,
                y:s.$el[0].height/2,
                z:-100
            }
        },opts);
        this.init_context();
    }

    Screen.prototype.init_context = function(){
        if(this.canvas){
            this.context = $(this.$el)[0].getContext("2d");
            this.context.fillStyle = this.opts.color;
        }
    };

    Screen.prototype.clear = function(){
        if(this.canvas){
            this.context.clearRect ( 0 , 0 , this.$el[0].width, this.$el[0].height );
        }
    };

    Screen.prototype.add = function(p){
        var c = p.toscreen(this.opts.focalpoint);
        var boundaryhit = false;

        if(c.x < 0){
            boundaryhit = true;
            c.x = this.$el[0].width - 4;
        } else if (c.x > this.$el[0].width){
            boundaryhit = true;
            c.x = 4;
        }

        if(c.y < 0){
            boundaryhit = true;
            c.y = this.$el[0].height - 4;
        } else if (c.y > this.$el[0].height){
            boundaryhit = true;
            c.y = 4;
        }

        if(c.z < 0){
            boundaryhit = true;
            c.z = this.opts.depth - 4;
        } else if (c.z > this.opts.depth){
            boundaryhit = true;
            c.z = 4;
        }


        if(boundaryhit){
            p.fromscreen(c, this.opts.focalpoint);
            c = p.toscreen(this.opts.focalpoint);
        }

        if(this.canvas){
            this.context.globalAlpha   = this.opts.minopacity + ((1-this.opts.minopacity) * (c.z/this.depth));
            this.context.beginPath();
            this.context.arc(c.x, c.y, 2 + 1 * (c.z/this.depth), 0, Math.PI * 2, false);
            this.context.closePath();
            this.context.fill();
        } else {
            this.$el.append(p.$el.css({
                left:c.x,
                top:c.y,
                backtround: this.opts.color,
                opacity: this.opts.minopacity + ((1-this.opts.minopacity) * (c.z/this.depth))
            }));
        }
    };

    Screen.prototype.draw = function(particles){
        var s = this;
        s.clear();
        for(var i =0;i<particles.length;i++){
            s.add(particles[i]);
        };
    };



    function Precipitate ($el, opts){
        var s = this;
        s.$el = $el;
        s.opts = $.extend(true, {
            speed:40,
            depth:1000,
            count:500
        }, opts );

        s.particles = [];
        s.screen = new Screen(s.$el, s.opts);

        for(var i =0; i<s.opts.count;i++){
            var p = new Particle({
                $el: s.screen.canvas?null:$('<div class="particle"></div>')
            });
            p.fromscreen({
                x: Math.round(Math.random() * s.$el[0].width),
                y: Math.round(Math.random() * s.$el[0].height),
                //particles should be more likely to be far away
                z: Math.round(Math.pow(Math.random(),4) * s.screen.depth)
                }, 
            s.screen.opts.focalpoint);
             s.particles.push(p);
        }

        setInterval(function(){
            for(var i =0; i<s.opts.count;i++){
                var p = s.particles[i];
                p.y -= 1;p.x -= 1; p.z += 0.1;
            }
            s.screen.draw(s.particles);
        },s.opts.speed);
    }

    if (!CCM_EDIT_MODE) {
        $('.ccm-block-custom-template-snow').each(function() {
            $(this).prepend('<div class="snow-effect-container"><canvas class="snow-effect-canvas"></canvas></div>');
        });

        if ($('.snow-effect-canvas').length > 0) {
            $('.snow-effect-canvas').each(function() {
                var w = $(window), canvas = $(this);
                canvas[0].width = w.width();
                canvas[0].height = w.height();
                var p = new Precipitate(canvas);
                w.resize(function(){
                    canvas[0].width = w.width();
                    canvas[0].height = w.height();
                    p.screen.init_context();
                });
            });
        }
    }
});