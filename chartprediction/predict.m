function [ranks] = predict(region)

    % Initialize
    ranks = zeros(10,1);
	
	% Load training data
	data = csvread(strcat('data/', strcat(region, '.train.csv')));
    if size(data,1) > 0
        X = process_data(data(:,2:end-1));
        y = data(:,end);

        % Run logistic regression for each of the 10 'categories' (ranks)
        options = optimset('GradObj', 'on', 'MaxIter', 100);
        T = cell(1);
        it = zeros(size(X,2),1);
        for i = 1:10
            [T{i},J] = fminunc(@(t)logreg_l2(X,y==i,t,0.1),it,options);
        end

        % Load data
        data = csvread(strcat('data/', strcat(region, '.data.csv')));
        if size(data,1) > 0
            data = data(:,2:end);

            % Use found weights to apply logistic regression to the current data
            predictions = zeros(size(data,1), 10);
            for i = 1:size(data,1)
                for j = 1:10
                    predictions(i,j) = sigmoid( -T{j}' * data(i,1:end)' );
                end
            end

            % Use found probabilities to determine the new chart list
            for i = 1:10
                [m,r] = max(predictions(:,i));
                predictions(r,:) = zeros(10, 1)-1;
                ranks(i) = r;
            end
        end
    end
	
end
