import React from 'react';
import ReactDOM from 'react-dom';
import './basket.scss';
import BasketItemsList from "../basket-items-list/basket-items-list";
import ApiService from "../../services/api_service";
import SendData from "../../services/send-data";
import SendCount from "../../services/send-count";
import UpdateRemove from "../../services/update_remove";


const sendData = new SendData();
const updateRemove = new UpdateRemove();
const sendCount = new SendCount();

const CountBasket = (props) => {
    return <span id={`side-panel-main__nav-count-basket`} className={`side-panel-main__nav-count`}>
        {props.count}
    </span>
};
const CountDelay = (props) => {
    return <span id={`side-panel-main__nav-count-delay`} className={`side-panel-main__nav-count`}>
        {props.count}
    </span>
};

export default class Basket extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            whatToShow: 'basket',
            inBasket: [],
            inDelay: [],
            lang: {},
            totalPrice: null,
            showEmpty: false,
            totalEconom: null,
            totalOldPrice: null
        };

        this.apiService = new ApiService();

        this.btnShowBasket = document.querySelector('.side-panel-main__nav-item--cart');
        this.btnShowFavorite = document.querySelector('.side-panel-main__nav-item--delay');

        this.btnShowBasket.addEventListener('click', () => {
            this.onBtnChangeShow('basket');
        });

        this.btnShowFavorite.addEventListener('click', () => {
            this.onBtnChangeShow('favorite');
        });

        this.updateItem = () => {
            this.apiService
                .getData()
                .then((data) => {
                    this.setState({
                        inBasket: data.CATEGORIES.READY ? data.CATEGORIES.READY : [],
                        inDelay: data.CATEGORIES.DELAY ? data.CATEGORIES.DELAY : [],
                        showInBasket: data.CATEGORIES.READY ? true : false,
                        showInDelay: data.CATEGORIES.DELAY ? true : false,
                        lang: data.LANG,
                        totalPrice: data.TOTAL_PRICE,
                        linkToCatalog: data.CATALOG_PATH,
                        linkToBasket: data.BASKET_PATH,
                        isShowOF: data.ORDER_PHONE_ACTIVE,
                        totalEconom: data.ECONOM_ITOGO_FORMAT,
                        totalOldPrice: data.OLD_PRICE_ITOGO_TOTAL,
                        countInBasket: data.CATEGORIES.READY ? data.CATEGORIES.READY.length : 0,
                        countInDelay: data.CATEGORIES.DELAY ? data.CATEGORIES.DELAY.length : 0
                    });
                });

        };

        window.basketLine = {
          update:  this.updateItem
        };

        this.updateStateResponse = (data) => {
            this.setState({
                inBasket: data.CATEGORIES.READY ? data.CATEGORIES.READY : [],
                inDelay: data.CATEGORIES.DELAY ? data.CATEGORIES.DELAY : [],
                totalPrice: data.TOTAL_PRICE,
                countInBasket: data.CATEGORIES.READY ? data.CATEGORIES.READY.length : 0,
                countInDelay: data.CATEGORIES.DELAY ? data.CATEGORIES.DELAY.length : 0
            });

        };

        this.onBtnChangeShow = (name) => {
            this.setState({
                whatToShow: name
            });
        };

        this.onInFavorite = (id) => {
            let newBasketArray = this.state.inBasket.filter((item) => {
                if (id !== item.PRODUCT_ID) {
                    return item;
                }
            });
            let newItemFavorite = this.state.inBasket.filter((item) => {
                if (id === item.PRODUCT_ID) {
                    item.DELAY = "Y";
                    return item;
                }
            });
            let newFavoriteArray = [...this.state.inDelay, ...newItemFavorite];
            this.setState({
                inBasket: newBasketArray,
                inDelay: newFavoriteArray,
            });
            newItemFavorite[0].ACTION = 'toFavorite';
            sendData.sendData(newItemFavorite[0]);
        };

        this.onInBasket = (id) => {
            let newFavoriteArray = this.state.inDelay.filter((item) => {
                if (id !== item.PRODUCT_ID) {
                    return item;
                }
            });
            let newItemBasket = this.state.inDelay.filter((item) => {
                if (id === item.PRODUCT_ID) {
                    item.DELAY = "N";
                    return item;
                }
            });
            let newBasketArray = [...this.state.inBasket, ...newItemBasket];
            this.setState({
                inBasket: newBasketArray,
                inDelay: newFavoriteArray,
            });
            newItemBasket[0].ACTION = 'toBasket';
            sendData.sendData(newItemBasket[0]);
        };

        this.updatePrice = (data) => {
            const removeBasketItems = this.state.inBasket.filter((item) => {
                if (item.IS_REMOVED) {
                    return item;
                }
            });
            const removeBasketDelay = this.state.inDelay.filter((item) => {
                if (item.IS_REMOVED) {
                    return item;
                }
            });
            this.setState({
                totalPrice: data.TOTAL_PRICE,
                totalEconom: data.ECONOM_ITOGO_FORMAT,
                totalOldPrice: data.OLD_PRICE_ITOGO_TOTAL,
                countInBasket: data.CATEGORIES.READY ? data.CATEGORIES.READY.length : 0,
                countInDelay: data.CATEGORIES.DELAY ? data.CATEGORIES.DELAY.length : 0
            });

        };

        this.deleteAll = () => {
            switch (this.state.whatToShow) {
                case "basket":
                    updateRemove.sendData({"DELETE_ALL": "Y"}, this.updatePrice, true);
                    this.setState({
                        inBasket: []
                    });
                    break;

                case "favorite":
                    updateRemove.sendData({"DELETE_ALL_DELAY": "Y"}, this.updatePrice, true)
                    this.setState({
                        inDelay: []
                    });
                    break;
            }
        };

        this.onBtnDelete = (id) => {
            switch (this.state.whatToShow) {
                case "basket":
                    let newBasketArray = this.state.inBasket.filter((item) => {
                        if (item.IS_REMOVED !== true) {
                            if (id === item.PRODUCT_ID) {
                                item.IS_REMOVED = true;
                                updateRemove.sendData(item, this.updatePrice)
                            }
                            return item;
                        }
                    });
                    this.setState({
                        inBasket: newBasketArray
                    });
                    break;

                case "favorite":
                    let newFavoriteArray = this.state.inDelay.filter((item) => {
                        if (item.IS_REMOVED !== true) {
                            if (id === item.PRODUCT_ID) {
                                if (id === item.PRODUCT_ID) {
                                    item.IS_REMOVED = true;
                                    updateRemove.sendData(item, this.updatePrice)
                                }
                            }
                            return item;
                        }
                    });
                    this.setState({
                        inDelay: newFavoriteArray
                    });
                    break;
            }
        };
        this.onBtnReturns = (id) => {
            switch (this.state.whatToShow) {
                case "basket":
                    let newBasketArray = this.state.inBasket.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            item.IS_REMOVED = false;
                            // updateRemove.sendData(item, this.updatePrice);
                            updateRemove.sendData(item, this.updateStateResponse)
                        }
                        return item;
                    });
                    this.setState({
                        inBasket: newBasketArray
                    });
                    break;

                case "favorite":
                    let newFavoriteArray = this.state.inDelay.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            if (id === item.PRODUCT_ID) {
                                item.IS_REMOVED = false;
                                // updateRemove.sendData(item, this.updateTotalPrice)
                                updateRemove.sendData(item, this.updateStateResponse)
                            }
                        }
                        return item;
                    });
                    this.setState({
                        inDelay: newFavoriteArray
                    });
                    break;
            }
        };

        this.onBtnMore = (id) => {
            switch (this.state.whatToShow) {
                case "basket":
                    let newBasketArray = this.state.inBasket.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            if (item.MAX_QUANTITY && (item.QUANTITY >= parseInt(item.MAX_QUANTITY) - parseInt(item.RATIO))) {
                                item.QUANTITY = parseInt(item.MAX_QUANTITY);
                                item.ACTION = 'count';
                                sendCount.sendData(item, this.updateStateResponse);
                                return item;
                            }
                            item.QUANTITY += parseFloat(item.RATIO);
                            item.ACTION = 'count';
                            sendCount.sendData(item, this.updateStateResponse);
                        }
                        return item;
                    });
                    this.setState({
                        inBasket: newBasketArray
                    });
                    break;

                case "favorite":
                    let newFavoriteArray = this.state.inDelay.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            if (item.MAX_QUANTITY && (item.QUANTITY >= parseFloat(item.MAX_QUANTITY) - parseFloat(item.RATIO))) {
                                item.QUANTITY = parseFloat(item.MAX_QUANTITY);
                                return item;
                            }
                            item.QUANTITY += parseFloat(item.RATIO);
                            item.ACTION = 'count';
                            sendCount.sendData(item, this.updateStateResponse);
                        }
                        return item;
                    });
                    this.setState({
                        inDelay: newFavoriteArray
                    });
                    break;
            }
        };

        this.onBtnLess = (id) => {
            switch (this.state.whatToShow) {
                case "basket":
                    let newBasketArray = this.state.inBasket.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            if (item.QUANTITY <= parseFloat(item.RATIO)) {
                                item.QUANTITY = parseFloat(item.RATIO);
                                return item;
                            }
                            item.QUANTITY -= parseFloat(item.RATIO);
                            item.ACTION = 'count';
                            sendCount.sendData(item, this.updateStateResponse);
                        }
                        return item;
                    });
                    this.setState({
                        inBasket: newBasketArray
                    });
                    break;

                case "favorite":
                    let newFavoriteArray = this.state.inDelay.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            if (item.QUANTITY <= parseFloat(item.RATIO)) {
                                item.QUANTITY = parseFloat(item.RATIO);
                                return item;
                            }
                            item.QUANTITY -= parseFloat(item.RATIO);
                            item.ACTION = 'count';
                            sendCount.sendData(item, this.updateStateResponse);
                        }
                        return item;
                    });
                    this.setState({
                        inDelay: newFavoriteArray
                    });
                    break;
            }
        };

        this.onChangeCount = (id, value) => {
            switch (this.state.whatToShow) {
                case "basket":
                    let newBasketArray = this.state.inBasket.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            item.QUANTITY = +value;
                        }
                        return item;
                    });
                    this.setState({
                        inBasket: newBasketArray
                    });
                    break;

                case "favorite":
                    let newFavoriteArray = this.state.inDelay.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            item.QUANTITY = +value;
                        }
                        return item;
                    });
                    this.setState({
                        inDelay: newFavoriteArray
                    });
                    break;
            }
        };

        this.onBlurCount = (id, value) => {
            if(!value) {
                switch (this.state.whatToShow) {
                    case "basket":
                        let newBasketArray = this.state.inBasket.map((item) => {
                            if (id === item.PRODUCT_ID) {
                                item.QUANTITY = parseFloat(item.RATIO);
                            }
                            return item;
                        });
                        this.setState({
                            inBasket: newBasketArray
                        });
                        break;

                    case "favorite":
                        let newFavoriteArray = this.state.inDelay.map((item) => {
                            if (id === item.PRODUCT_ID) {
                                item.QUANTITY = parseFloat(item.RATIO);
                            }
                            return item;
                        });
                        this.setState({
                            inDelay: newFavoriteArray
                        });
                        break;
                }
            }

            switch (this.state.whatToShow) {
                case "basket":
                    let newBasketArray = this.state.inBasket.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            item.ACTION = 'count';
                            sendData.sendData(item, this.updateStateResponse);
                        }
                    });
                    break;

                case "favorite":
                    let newFavoriteArray = this.state.inDelay.map((item) => {
                        if (id === item.PRODUCT_ID) {
                            item.ACTION = 'count';
                            sendData.sendData(item, this.updateStateResponse);
                        }
                    });
                    break;

            }

        };

    };

    UNSAFE_componentWillMount() {
        this.updateItem();
    };

    componentDidUpdate(prevProps, prevState, snapshot) {
        this.sidePanelMainBasket = document.querySelector('.basket__content');
        if(this.perfectScroll && this.sidePanelMainBasket) {
            this.perfectScroll.update();
        }

        if(!this.perfectScroll && this.sidePanelMainBasket) {
            this.perfectScroll = new PerfectScrollbar(this.sidePanelMainBasket,{
                wheelSpeed: 0.5,
                wheelPropagation: true,
                minScrollbarLength: 20
            });
        }
    };

    render () {

        const {lang, totalPrice, totalEconom, totalOldPrice} = this.state;
        return (
          <div className={`basket`}>
              <div className={`basket__header-wrapper`}>
                  <div className={`basket__header`}>
                  <p className={`basket__header-title`}>{lang.REACT_BASKET_NAME}</p>
                  <a href={'/'} className={`basket__header-in-basket
                     ${this.state.whatToShow == 'basket' ? 'active' : ''}`}
                     onClick={(e) => {
                         e.preventDefault()
                         this.onBtnChangeShow('basket');
                     }}>
                          {lang.REACT_IN_BASKET}
                          <span>{` (${this.state.countInBasket})`}</span>
                  </a>
                  <a href={`/`} className={`basket__header-aside
                     ${this.state.whatToShow == 'favorite' ? 'active' : ''}`}
                     onClick={(e) => {
                         e.preventDefault();
                         this.onBtnChangeShow('favorite')
                     }}>
                        {lang.REACT_DELAY_PRODUCTS}
                        <span>{` (${this.state.countInDelay})`}</span>
                  </a>
                      {this.state.whatToShow === 'basket' ? (
                          <button type={`button`}
                                  className={`basket__header-clear`}
                                  onClick={this.deleteAll}>{lang.REACT_CLEAR_PRODUCTS}</button>
                      )  : (
                          <button type={`button`}
                                  className={`basket__header-clear`}
                                  onClick={this.deleteAll}>{lang.REACT_BASKET_CLEAN_DELAY}</button>
                      )}
              </div>
                  <div className={`basket__header-description ${
                      (this.state.whatToShow == 'basket' && this.state.inBasket.length) ||
                      (this.state.whatToShow == 'favorite' && this.state.inDelay.length) ? '' : 'hide'
                  }`}>
                      <p className={`basket__header-description-name`}>{lang.REACT_BASKET_COLUMN_NAME}</p>
                      <p className={`basket__header-description-price`}>{lang.REACT_BASKET_COLUMN_PRICE}</p>
                      <p className={`basket__header-description-count`}>{lang.REACT_BASKET_COLUMN_COUNT}</p>
                      <p className={`basket__header-description-sum`}>{lang.REACT_BASKET_COLUMN_SUM}</p>
                  </div>
              </div>
              <div className={`basket__content`}>
                  <BasketItemsList
                                    itemsBasket={this.state.inBasket}
                                    itemsDelay={this.state.inDelay}
                                    whatToShow={this.state.whatToShow}
                                    showInBasket={this.state.showInBasket}
                                    showInDelay={this.state.showInDelay}
                                    title={this.state.lang}
                                    linkToCatalog={this.state.linkToCatalog}
                                    onInFavorite={this.onInFavorite}
                                    onInBasket={this.onInBasket}
                                    onBtnMore={this.onBtnMore}
                                    onBtnLess={this.onBtnLess}
                                    onBlurCount={this.onBlurCount}
                                    onChangeCount={this.onChangeCount}
                                    onChangeEmpty={this.onChangeEmpty}
                                    onDeleteItem={this.onBtnDelete}
                                    onBtnReturns={this.onBtnReturns}
                  />
              </div>
              <div className={`basket__footer-wrapper ${this.state.whatToShow == 'basket' && (this.state.inBasket.length !== 0) ? '' : 'hide-block'}`}>
                  <div className={`basket__footer-total-wrapper`}>
                      {totalEconom ? (
                          <p className={`basket__footer-total-discount`}>{lang.REACT_BASKET_DISCOUNT} <span dangerouslySetInnerHTML={{__html: totalEconom}}/></p>
                      ) : null}
                      <div className={`basket__footer-total-price`}>
                          <p className={`basket__footer-total`}>{lang.REACT_TOTAL} <span dangerouslySetInnerHTML={{__html: totalPrice}}/></p>
                          {totalOldPrice ? (
                            <p className={`basket__footer-total-old`} dangerouslySetInnerHTML={{__html: totalOldPrice}}/>
                          ) : null}
                      </div>

                  </div>
                  <div className={`basket__footer`}>
                      <a href={this.state.linkToCatalog}
                         className={`basket__footer-continue-product`}
                         onClick={(e) => {
                             e.preventDefault();
                             window.rightPanel.closePanel();
                         }}>
                          <svg width="10" height="10">
                              <use xlinkHref="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_right"></use>
                          </svg>
                          {lang.REACT_CONTINUE_PRODUCTS}
                      </a>
                      {this.state.isShowOF ?
                        <button className={`basket__footer-quick-order`} id={`order_oc_top`}>{lang.REACT_QUICK_ORDER}</button> :
                        ''
                      }
                      <a href={this.state.linkToBasket} className={`basket__footer-go-to-basket`}>{lang.REACT_GO_TO_BASKET}</a>
                      <a href="" className={`basket__footer-checkout`}
                      onClick={(e)=> {
                          window.rightPanel.showOrder(e);
                          window.rightPanel.initOrder();
                      }}>{lang.REACT_BASKET_CHECKOUT_ORDER}</a>
                  </div>
              </div>
              {ReactDOM.createPortal(<CountBasket count={this.state.countInBasket}/>, document.querySelector('.side-panel-main__nav-item--cart'))}
              {ReactDOM.createPortal(<CountDelay count={this.state.countInDelay}/>, document.querySelector('.side-panel-main__nav-item--delay'))}
          </div>
        );
    }
};
