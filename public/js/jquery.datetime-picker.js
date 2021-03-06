/*********************************************** 
     Begin timepicker-min.js 
***********************************************/

(function (e) {
    jQuery.fn.datetimepicker = function () {
        this.each(function () {
            var t = this.id,
                n = e(this).val(),
                r = new Array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24"),
                i = new Array("00", "15", "30", "45"),
                s = new Array("00", "15", "30", "45"),
                o = new Date,
                u = o.getHours(),
                a = o.getMinutes(),
                f = u >= 12 ? "pm" : "am";
            /*u > 12 && (u -= 12);
            for (mn in i) if (a <= parseInt(i[mn])) {
                a = parseInt(i[mn]);
                break
            }
            if (a > 45) {
                a = 0;
                switch (u) {
                    case 11:
                        u += 1;
                        f = f == "am" ? "pm" : "am";
                        break;
                    case 12:
                        u = 1;
                        break;
                    default:
                        u += 1
                }
            }
            if (n.length == 7) {
                u = parseInt(n.substr(0, 2));
                a = parseInt(n.substr(3, 2));
                f = n.substr(5)
            }*/
            if(e(this).val().length==8) {
            	n = e(this).val();
            	u = parseInt(n.substr(0, 2));
                a = parseInt(n.substr(3, 2));
                f = parseInt(n.substr(6, 2));
            }
            var l = "";
            l += '<select id="h_' + t + '" class="h timepicker">';
            for (hr in r) {
                l += '<option value="' + r[hr] + '"';
                parseInt(r[hr]) == u && (l += " selected");
                l += ">" + r[hr] + "</option>"
            }
            l += "</select>";
            l += '<select id="m_' + t + '" class="m timepicker">';
            for (mn in i) {
                l += '<option value="' + i[mn] + '"';
                parseInt(i[mn]) == a && (l += " selected");
                l += ">" + i[mn] + "</option>"
            }
            l += "</select>";
            l += '<select id="p_' + t + '" class="p timepicker">';
            for (pp in s) {
                l += '<option value="' + s[pp] + '"';
                s[pp] == f && (l += " selected");
                l += ">" + s[pp] + "</option>"
            }
            l += "</select>";
            //e(this).attr("type", "hidden").after(l)
            e(this).parent().append(l);
        });
        e("select.timepicker").change(function () {
            var t = this.id.substr(2),
                n = e("#h_" + t).val(),
                r = e("#m_" + t).val(),
                i = e("#p_" + t).val(),
                s = n + ":" + r + ":" + i;
            e("#" + t).val(s)
        });
        return this
    }
})(jQuery);
