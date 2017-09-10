Raphael.fn.drawGrid = function (x, y, w, h, wv, hv, color) {
    color = color || "#000";
    var path = ["M", Math.round(x) + .5, Math.round(y) + .5, "L", Math.round(x + w) + .5, Math.round(y) + .5, Math.round(x + w) + .5, Math.round(y + h) + .5, Math.round(x) + .5, Math.round(y + h) + .5, Math.round(x) + .5, Math.round(y) + .5],
    rowHeight = h / hv,
    columnWidth = w / wv;
    for (var i = 1; i < hv; i++) {
        path = path.concat(["M", Math.round(x) + .5, Math.round(y + i * rowHeight) + .5, "H", Math.round(x + w) + .5]);
    }
    for (i = 1; i < wv; i++) {
        path = path.concat(["M", Math.round(x + i * columnWidth) + .5, Math.round(y) + .5, "V", Math.round(y + h) + .5]);
    }
    return this.path(path.join(",")).attr({
        stroke: color
    });
};
Raphael.fn.drawTitle = function (x, y, title, color) {
    color = color || "#000";
    this.text(x, y,title);
}
function getAnchors(p1x, p1y, p2x, p2y, p3x, p3y) {
    var l1 = (p2x - p1x) / 2,
    l2 = (p3x - p2x) / 2,
    a = Math.atan((p2x - p1x) / Math.abs(p2y - p1y)),
    b = Math.atan((p3x - p2x) / Math.abs(p2y - p3y));
    a = p1y < p2y ? Math.PI - a : a;
    b = p3y < p2y ? Math.PI - b : b;
    var alpha = Math.PI / 2 - ((a + b) % (Math.PI * 2)) / 2,
    dx1 = l1 * Math.sin(alpha + a),
    dy1 = l1 * Math.cos(alpha + a),
    dx2 = l2 * Math.sin(alpha + b),
    dy2 = l2 * Math.cos(alpha + b);
    return {
        x1: p2x - dx1,
        y1: p2y + dy1,
        x2: p2x + dx2,
        y2: p2y + dy2
    };
}
function drawChart(position,labels,data) {
    // Draw
    var width = 1200,
    height = 250,
    leftgutter = 30,
    bottomgutter = 20,
    topgutter = 100,
    colorhue = .6 || Math.random(),
    color = "hsl(" + [colorhue, .5, .5] + ")",
    r = Raphael("hold"+position, width, height),
    txt = {
        font: '12px Helvetica, Arial',
        fill: "#fff"
    },
    txt1 = {
        font: '10px Helvetica, Arial',
        fill: "#fff"
    },
    txt2 = {
        font: '12px Helvetica, Arial',
        fill: "#000"
    },
    X = (width - leftgutter) / labels.length,
    max = Math.max.apply(Math, data),
    Y = (height - bottomgutter - topgutter) / max,
    min = Math.min.apply(Math, data);
    r.drawGrid(leftgutter + X * .5 + .5, topgutter + .5, width - leftgutter - X, height - topgutter - bottomgutter, 20, 5, "#000");
    var path = r.path().attr({
        stroke: color,
        "stroke-width": 4,
        "stroke-linejoin": "round"
    }),
    bgp = r.path().attr({
        stroke: "none",
        opacity: .3,
        fill: color
    }),
    label = r.set(),
    lx = 0, ly = 0,
    is_label_visible = false,
    leave_timer,
    blanket = r.set();
    label.push(r.text(60, 12, "24 hits").attr(txt));
    label.push(r.text(60, 27, "22 September 2008").attr(txt1).attr({
        fill: color
    }));
    label.hide();
    var frame = r.popup(100, 100, label, "right").attr({
        fill: "#000",
        stroke: "#666",
        "stroke-width": 2,
        "fill-opacity": .7
    }).hide();

    var p, bgpp;
    for (var i = 0, ii = labels.length; i < ii; i++) {
        var y = Math.round(height - bottomgutter - Y * data[i]),
        x = Math.round(leftgutter + X * (i + .5)),
        t = r.text(x, height - 6, labels[i]).attr(txt).toBack();
        if (!i) {
            p = ["M", x, y, "C", x, y];
            bgpp = ["M", leftgutter + X * .5, height - bottomgutter, "L", x, y, "C", x, y];
        }
        if (i && i < ii - 1) {
            var Y0 = Math.round(height - bottomgutter - Y * data[i - 1]),
            X0 = Math.round(leftgutter + X * (i - .5)),
            Y2 = Math.round(height - bottomgutter - Y * data[i + 1]),
            X2 = Math.round(leftgutter + X * (i + 1.5));
            var a = getAnchors(X0, Y0, x, y, X2, Y2);
            p = p.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
            bgpp = bgpp.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
        }
        var dot = r.circle(x, y, 4).attr({
            fill: "#333",
            stroke: color,
            "stroke-width": 2
        });
        blanket.push(r.rect(leftgutter + X * i, 0, X, height - bottomgutter).attr({
            stroke: "none",
            fill: "#fff",
            opacity: 0
        }));
        var rect = blanket[blanket.length - 1];
        (function (x, y, data, lbl, dot) {
            var timer, i = 0;
            rect.hover(function () {
                clearTimeout(leave_timer);
                var side = "right";
                if (x + frame.getBBox().width > width) {
                    side = "left";
                }
                var ppp = r.popup(x, y, label, side, 1),
                anim = Raphael.animation({
                    path: ppp.path,
                    transform: ["t", ppp.dx, ppp.dy]
                }, 200 * is_label_visible);
                lx = label[0].transform()[0][1] + ppp.dx;
                ly = label[0].transform()[0][2] + ppp.dy;
                frame.show().stop().animate(anim);
                label[0].attr({
                    text: data
                }).show().stop().animateWith(frame, anim, {
                    transform: ["t", lx, ly]
                }, 200 * is_label_visible);
                label[1].attr({
                    text: lbl
                }).show().stop().animateWith(frame, anim, {
                    transform: ["t", lx, ly]
                }, 200 * is_label_visible);
                dot.attr("r", 6);
                is_label_visible = true;
            }, function () {
                dot.attr("r", 4);
                leave_timer = setTimeout(function () {
                    frame.hide();
                    label[0].hide();
                    label[1].hide();
                    is_label_visible = false;
                }, 1);
            });
        })(x, y, data[i], labels[i], dot);
    }
    p = p.concat([x, y, x, y]);
    bgpp = bgpp.concat([x, y, x, y, "L", x, height - bottomgutter, "z"]);
    path.attr({
        path: p
    });
    bgp.attr({
        path: bgpp
    });
    frame.toFront();
    label[0].toFront();
    label[1].toFront();
    blanket.toFront();
}
function drawCharttest(twidth,position,labels,data,data_unit,data_n1,data_n2,data_n3,data_n4,data_max1,data_max2,data_min1,data_min2) {
    // Draw
    var width = twidth,
    height = 250,
    leftgutter = 30,
    bottomgutter = 20,
    topgutter = 50,
    colorhue = .6 || Math.random(),
    color = "hsl(" + [colorhue, .5, .5] + ")",
    r = Raphael("hold"+position, width, height),
    txt = {
        font: '12px Helvetica, Arial',
        fill: "#fff"
    },
    txt1 = {
        font: '10px Helvetica, Arial',
        fill: "#fff"
    },
    txt2 = {
        font: '12px Helvetica, Arial',
        fill: "#000"
    },
    X = (width - leftgutter) / labels.length;
    max = Math.max.apply(Math,data);
//    max2 = Math.max.apply(Math,data_max2);
/*    if (max < max2){
        max =  max2;
    }
*/
    Y = (height - bottomgutter - topgutter) / max;
    min = Math.min.apply(Math, data);
    r.drawGrid(leftgutter + X * .5 + .5, topgutter + .5, width - leftgutter - X, height - topgutter - bottomgutter, labels.length-1, 5, "#000");
    var path = r.path().attr({
        stroke: color,
        "stroke-width": 4,
        "stroke-linejoin": "round"
    }),
    bgp = r.path().attr({
        stroke: "none",
        opacity: .3,
        fill: color
    }),
    label = r.set(),
    lx = 0, ly = 0,
    is_label_visible = false,
    leave_timer,
    blanket = r.set();
    label.push(r.text(60, 12, "24 hits").attr(txt));
    label.push(r.text(60, 27, "22 September 2008").attr(txt1).attr({
        fill: color
    }));
    label.hide();
    var frame = r.popup(100, 100, label, "right").attr({
        fill: "#000",
        stroke: "#666",
        "stroke-width": 2,
        "fill-opacity": .7
    }).hide();

    var p, bgpp;
    for (var i = 0, ii = labels.length; i < ii; i++) {
        var y = Math.round(height - bottomgutter - Y * data[i]),
        x = Math.round(leftgutter + X * (i + .5)),
        t = r.text(x, height - 6, labels[i]).attr(txt).toBack();
        if (!i) {
            p = ["M", x, y, "C", x, y];
            bgpp = ["M", leftgutter + X * .5, height - bottomgutter, "L", x, y, "C", x, y];
        }
        if (i && i < ii - 1) {
            var Y0 = Math.round(height - bottomgutter - Y * data[i - 1]),
            X0 = Math.round(leftgutter + X * (i - .5)),
            Y2 = Math.round(height - bottomgutter - Y * data[i + 1]),
            X2 = Math.round(leftgutter + X * (i + 1.5));
            var a = getAnchors(X0, Y0, x, y, X2, Y2);
            p = p.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
            bgpp = bgpp.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
        }
        var dot = r.circle(x, y, 4).attr({
            fill: "#333",
            stroke: color,
            "stroke-width": 2
        });
        blanket.push(r.rect(leftgutter + X * i, 0, X, height - bottomgutter).attr({
            stroke: "none",
            fill: "#fff",
            opacity: 0
        }));
        var rect = blanket[blanket.length - 1];
        (function (x, y, data, lbl, dot) {
            var timer, i = 0;
            rect.hover(function () {
                clearTimeout(leave_timer);
                var side = "right";
                if (x + frame.getBBox().width > width) {
                    side = "left";
                }
                var ppp = r.popup(x, y, label, side, 1),
                anim = Raphael.animation({
                    path: ppp.path,
                    transform: ["t", ppp.dx, ppp.dy]
                }, 200 * is_label_visible);
                lx = label[0].transform()[0][1] + ppp.dx;
                ly = label[0].transform()[0][2] + ppp.dy;
                frame.show().stop().animate(anim);
                label[0].attr({
                    text: data
                }).show().stop().animateWith(frame, anim, {
                    transform: ["t", lx, ly]
                }, 200 * is_label_visible);
                label[1].attr({
                    text: lbl
                }).show().stop().animateWith(frame, anim, {
                    transform: ["t", lx, ly]
                }, 200 * is_label_visible);
                dot.attr("r", 6);
                is_label_visible = true;
            }, function () {
                dot.attr("r", 4);
                leave_timer = setTimeout(function () {
                    frame.hide();
                    label[0].hide();
                    label[1].hide();
                    is_label_visible = false;
                }, 1);
            });
        })(x, y, data[i]+data_unit[i]+"\n正常"+data_n1[i]+"次"+"\n预警"+data_n2[i]+"次"+"\n故障"+data_n3[i]+"次"+"\n事故"+data_n4[i]+"次", "", dot);
    }
    p = p.concat([x, y, x, y]);
    bgpp = bgpp.concat([x, y, x, y, "L", x, height - bottomgutter, "z"]);
    path.attr({
        path: p
    });
    bgp.attr({
        path: bgpp
    });
    frame.toFront();
    label[0].toFront();
    label[1].toFront();
    blanket.toFront();
}
window.onload = function () {

    // Grab the data
    var labels = [],
    data = [],
    data_unit = [],
    data_n1 = [],
    data_n2 = [],
    data_n3 = [],
    data_n4 = [],
    data_max1 = [],
    data_max2 = [],
    data_min1 = [],
    data_min2 = [];

    $("#daydata tfoot th").each(function () {
        labels.push($(this).html());
    });
    $("#daydata tbody #data td").each(function () {
        data.push($(this).html());
    });
    $("#daydata tbody #unit td").each(function () {
        data_unit.push($(this).html());
    });
    $("#daydata tbody #n1 td").each(function () {
        data_n1.push($(this).html());
    });
    $("#daydata tbody #n2 td").each(function () {
        data_n2.push($(this).html());
    });
    $("#daydata tbody #n3 td").each(function () {
        data_n3.push($(this).html());
    });
    $("#daydata tbody #n4 td").each(function () {
        data_n4.push($(this).html());
    });
    $("#daydata tbody #max1 td").each(function () {
        data_max1.push($(this).html());
    });
    $("#daydata tbody #max2 td").each(function () {
        data_max2.push($(this).html());
    });
    $("#daydata tbody #min1 td").each(function () {
        data_min1.push($(this).html());
    });
    $("#daydata tbody #min2 td").each(function () {
        data_min2.push($(this).html());
    });
    drawCharttest(1200,"daydata",labels,data,data_unit,data_n1,data_n2,data_n3,data_n4,data_max1,data_max2,data_min1,data_min2);
    //drawChart("daydata",labels,data);
    // Grab the data
    labels = [],
    data = [],
    data_unit = [],
    data_n1 = [],
    data_n2 = [],
    data_n3 = [],
    data_n4 = [],
    data_max1 = [],
    data_max2 = [],
    data_min1 = [],
    data_min2 = [];
    $("#weekdata tfoot th").each(function () {
        labels.push($(this).html());
    });
    $("#weekdata tbody #data td").each(function () {
        data.push($(this).html());
    });
    $("#weekdata tbody #unit td").each(function () {
        data_unit.push($(this).html());
    });
    $("#weekdata tbody #n1 td").each(function () {
        data_n1.push($(this).html());
    });
    $("#weekdata tbody #n2 td").each(function () {
        data_n2.push($(this).html());
    });
    $("#weekdata tbody #n3 td").each(function () {
        data_n3.push($(this).html());
    });
    $("#weekdata tbody #n4 td").each(function () {
        data_n4.push($(this).html());
    });
    $("#weekdata tbody #max1 td").each(function () {
        data_max1.push($(this).html());
    });
    $("#weekdata tbody #max2 td").each(function () {
        data_max2.push($(this).html());
    });
    $("#weekdata tbody #min1 td").each(function () {
        data_min1.push($(this).html());
    });
    $("#weekdata tbody #min2 td").each(function () {
        data_min2.push($(this).html());
    });
    drawCharttest(600,"weekdata",labels,data,data_unit,data_n1,data_n2,data_n3,data_n4,data_max1,data_max2,data_min1,data_min2);

    // Grab the data
    labels = [],
    data = [],
    data_unit = [],
    data_n1 = [],
    data_n2 = [],
    data_n3 = [],
    data_n4 = [],
    data_max1 = [],
    data_max2 = [],
    data_min1 = [],
    data_min2 = [];
    $("#monthdata tfoot th").each(function () {
        labels.push($(this).html());
    });
    $("#monthdata tbody #data td").each(function () {
        data.push($(this).html());
    });
    $("#monthdata tbody #unit td").each(function () {
        data_unit.push($(this).html());
    });
    $("#monthdata tbody #n1 td").each(function () {
        data_n1.push($(this).html());
    });
    $("#monthdata tbody #n2 td").each(function () {
        data_n2.push($(this).html());
    });
    $("#monthdata tbody #n3 td").each(function () {
        data_n3.push($(this).html());
    });
    $("#monthdata tbody #n4 td").each(function () {
        data_n4.push($(this).html());
    });
    $("#monthdata tbody #max1 td").each(function () {
        data_max1.push($(this).html());
    });
    $("#monthdata tbody #max2 td").each(function () {
        data_max2.push($(this).html());
    });
    $("#monthdata tbody #min1 td").each(function () {
        data_min1.push($(this).html());
    });
    $("#monthdata tbody #min2 td").each(function () {
        data_min2.push($(this).html());
    });
    drawCharttest(600,"monthdata",labels,data,data_unit,data_n1,data_n2,data_n3,data_n4,data_max1,data_max2,data_min1,data_min2);




};