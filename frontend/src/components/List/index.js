function List() {
    return <div className="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
        <div className="list-group">
            <label className="list-group-item d-flex gap-2">
                <input className="form-check-input flex-shrink-0" type="checkbox" value="" checked=""></input>
                <span>
        First checkbox
        <small className="d-block text-body-secondary">With support text underneath to add more detail</small>
      </span>
            </label>
            <label className="list-group-item d-flex gap-2">
                <input className="form-check-input flex-shrink-0" type="checkbox" value=""></input>
                <span>
        Second checkbox
        <small className="d-block text-body-secondary">Some other text goes here</small>
      </span>
            </label>
            <label className="list-group-item d-flex gap-2">
                <input className="form-check-input flex-shrink-0" type="checkbox" value=""></input>
                <span>
        Third checkbox
        <small className="d-block text-body-secondary">And we end with another snippet of text</small>
      </span>
            </label>
        </div>

        <div className="list-group">
            <label className="list-group-item d-flex gap-2">
                <input className="form-check-input flex-shrink-0" type="radio" name="listGroupRadios"
                       id="listGroupRadios1" value="" checked=""></input>
                <span>
        First radio
        <small className="d-block text-body-secondary">With support text underneath to add more detail</small>
      </span>
            </label>
            <label className="list-group-item d-flex gap-2">
                <input className="form-check-input flex-shrink-0" type="radio" name="listGroupRadios"
                       id="listGroupRadios2" value=""></input>
                <span>
        Second radio
        <small className="d-block text-body-secondary">Some other text goes here</small>
      </span>
            </label>
            <label className="list-group-item d-flex gap-2">
                <input className="form-check-input flex-shrink-0" type="radio" name="listGroupRadios"
                       id="listGroupRadios3" value=""></input>
                <span>
        Third radio
        <small className="d-block text-body-secondary">And we end with another snippet of text</small>
      </span>
            </label>
        </div>
    </div>
}

export default List;
