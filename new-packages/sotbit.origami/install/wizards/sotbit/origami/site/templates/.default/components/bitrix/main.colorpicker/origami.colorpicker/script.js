function BXColorPicker(oPar/*, pLEditor*/)
{
	if (!oPar.name)
		oPar.name = oPar.id;
	if (!oPar.title)
		oPar.title = oPar.name;
	this.disabled = false;
	this.bCreated = false;
	this.bOpened = false;
	this.zIndex = oPar.zIndex ? oPar.zIndex : 1000;
	this.fid = oPar.id.toLowerCase(); // + '_' + pLEditor.id;

	//this.pLEditor = pLEditor;
	this.oPar = oPar;

	this.oneGifSrc = '/bitrix/images/1.gif';

	this.BeforeCreate();
}

BXColorPicker.prototype.BeforeCreate = function()
{
	var _this = this;
	this.pWnd = BX.create("DIV", {
		props: {
			// src: this.oneGifSrc,
			// title: this.oPar.title,
			className: "bx-colpic-button bx-colpic-button-normal",
			id: "bx_btn_" + this.oPar.id.toLowerCase()
		}
    });

    this.pWnd.innerHTML=  '<svg class="colorpicker-icon" width="18" height="18">' +
                                '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#color"></use>' +
                            '</svg>';

    // this.pWnd = '<svg class="site-navigation__item-icon" width="18" height="18">' +
    //                 '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon-search"></use>' +
    //             '</svg>'

	this.pWnd.onmouseover = function(e){_this.OnMouseOver(e, this)};
	this.pWnd.onmouseout = function(e){_this.OnMouseOut(e, this)};
	this.pWnd.onclick = function(e){_this.OnClick(e, this)};
	this.pCont = BX.create("DIV", {props: {className: 'bx-colpic-button-cont'}});
	this.pCont.appendChild(this.pWnd);

}

BXColorPicker.prototype.Create = function ()
{
	var _this = this;
	window['bx_colpic_keypress_' + this.fid] = function(e){_this.OnKeyPress(e);};
	window['bx_colpic_click_' + this.fid] = function(e){_this.OnDocumentClick(e);};

	this.pColCont = document.body.appendChild(BX.create("DIV", {props: {className: "bx-colpic-cont"}, style: {zIndex: this.zIndex}}));

	var arColors = [
        '','','','','','','','',
        '#FEDF2A', '#F6931C', '#F14E23', '#E31C30', '#42407F', '#006CB7', '#005BA4', '#2D212D',
        '#FFF36C', '#FDAF17', '#F58466', '#EF5972', '#7D78B8', '#1290CA', '#1290CA', '#727272',
        '#FEF8A1', '#FECD67', '#F69680', '#F48F9F', '#A6A0D0', '#53B7E8', '#55B7E8', '#B2B3B7',
        '#FCF8B8', '#FEE27E', '#F8AA96', '#F7ADAE', '#BCB8DD', '#73BDEA', '#74BEEB', '#C6C7C9',
        '#FEFBCE', '#FEE191', '#FBC7BA', '#F8BFC6', '#D4D2E8', '#90D7ED', '#8FCEF1', '#DCDDDF',
        '#FFFCDF', '#FFEDAA', '#FDDDD0', '#FAD2D2', '#E1DEEF', '#B9E3EF', '#A9DFF9', '#EDEDED'
	];

	var
		row, cell, colorCell,
		tbl = BX.create("TABLE", {props:{className: 'bx-colpic-tbl'}}),
		i, l = arColors.length;

	row = tbl.insertRow(-1);
	cell = row.insertCell(-1);
	cell.colSpan = 4;
	var defBut = cell.appendChild(BX.create("SPAN", {props:{className: 'bx-colpic-def-but'}}));
	defBut.innerHTML = window.jsColorPickerMess.DefaultColor;
	defBut.onmouseover = function()
	{
		this.className = 'bx-colpic-def-but bx-colpic-def-but-over';
		colorCell.style.backgroundColor = 'transparent';
	};
	defBut.onmouseout = function(){this.className = 'bx-colpic-def-but';};
	defBut.onclick = function(e){_this.Select(false);}

    colorCell = row.insertCell(-1);

	colorCell.colSpan = 4;
    colorCell.className = 'bx-color-inp-cell';
    colorCell.innerHTML = '<div class="bx-color-inp-block"></div>';
	colorCell.style.backgroundColor = arColors[38];

	for(i = 0; i < l; i++)
	{
		if (Math.round(i / 8) == i / 8) // new row
			row = tbl.insertRow(-1);

		cell = row.insertCell(-1);
		cell.innerHTML = '&nbsp;';
        cell.className = 'bx-colpic-col-cell';

		cell.style.backgroundColor = arColors[i];
		cell.id = 'bx_color_id__' + i;

		cell.onmouseover = function (e)
		{
			this.className = 'bx-colpic-col-cell bx-colpic-col-cell-over';
			colorCell.children[0].style.backgroundColor = arColors[this.id.substring('bx_color_id__'.length)];
		};
		cell.onmouseout = function (e){this.className = 'bx-colpic-col-cell';};
		cell.onclick = function (e)
		{
			var k = this.id.substring('bx_color_id__'.length);
			_this.Select(arColors[k]);
		};
	}

    this.pColCont.appendChild(tbl);
	this.bCreated = true;
};


BXColorPicker.prototype.OnClick = function (e, pEl)
{
	if(this.disabled)
		return false;

	if (!this.bCreated)
		this.Create();
	if (this.bOpened)
		return this.Close();

	this.Open();
};

BXColorPicker.prototype.Open = function ()
{
	var
		pos = BX.align(this.pWnd, 240, 130),
		_this = this;

	//this.pLEditor.oPrevRange = this.pLEditor.GetSelectionRange();
	BX.bind(window, "keypress", window['bx_colpic_keypress_' + this.fid]);
	BX.defer(function(){BX.bind(window, "click", window['bx_colpic_click_' + _this.fid]);})();
	//pOverlay.onclick = function(){_this.Close()};

	this.pColCont.style.display = 'block';
	this.pColCont.style.top = pos.top + 10 + 'px'; //<== correction position
	this.pColCont.style.left = pos.left - 5 + 'px'; //<== correction position

    this.bOpened = true;
    this.wheel('Y');
}

BXColorPicker.prototype.Close = function ()
{
	this.pColCont.style.display = 'none';
	//this.pLEditor.oTransOverlay.Hide();
	BX.unbind(window, "keypress", window['bx_colpic_keypress_' + this.fid]);
	BX.unbind(window, "click", window['bx_colpic_click_' + this.fid]);

    this.bOpened = false;
    this.wheel('N');
}

BXColorPicker.prototype.OnMouseOver = function (e, pEl)
{
	if(this.disabled)
		return;
	pEl.className = 'bx-colpic-button bx-colpic-button-over';
}

BXColorPicker.prototype.OnMouseOut = function (e, pEl)
{
	if(this.disabled)
		return;
	pEl.className = 'bx-colpic-button bx-colpic-button-normal';
}

BXColorPicker.prototype.OnKeyPress = function(e)
{
	if(!e)
		e = window.event;
	if(e.keyCode == 27)
		this.Close();
};

BXColorPicker.prototype.OnDocumentClick = function (e)
{
	if(!e)
		e = window.event;

	var target = e.target || e.srcElement;
	if (target && !BX.findParent(target, {className: 'bx-colpic-cont'}))
		this.Close();
};

BXColorPicker.prototype.Select = function (color)
{
	//this.pLEditor.SelectRange(this.pLEditor.oPrevRange);
	if (this.oPar.OnSelect && typeof this.oPar.OnSelect == 'function')
		this.oPar.OnSelect(color, this);
	this.Close();
};


BXColorPicker.prototype.notWheel = function (evt) {
    evt.preventDefault();
}

BXColorPicker.prototype.wheel = function (params) {
    let uxPanel = document.querySelector('.landing-ui-design');
    let colorpicker = document.querySelector('.bx-colpic-cont');
    let colorpickerOverlay = document.querySelector('.overlay');

    if (params === "Y" && uxPanel && colorpicker && colorpickerOverlay) {
        uxPanel.addEventListener('wheel', this.notWheel);
        colorpicker.addEventListener('wheel', this.notWheel);
        colorpickerOverlay.addEventListener('wheel', this.notWheel);
    }

    if (params === "N") {
        uxPanel.removeEventListener('wheel', this.notWheel);
        colorpicker.removeEventListener('wheel', this.notWheel);
        colorpickerOverlay.removeEventListener('wheel', this.notWheel);
    }
}


