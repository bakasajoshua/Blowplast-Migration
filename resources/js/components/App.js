import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class App extends Component {
    render() {
        return (
            <div className="flex-center position-ref full-height">
                <div className="content">
                    <div className="title m-b-md">
                        Laravel
                    </div>

                    <div className="links">
                        <a href="#">Docs</a>
                        <a href="#">Laracasts</a>
                        <a href="#">News</a>
                        <a href="#">Blog</a>
                    </div>                    
                </div>
            </div>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
