function [score] = test(region)

    % Initialize
    score = 0;
	
	% Load training data
	data = csvread(strcat('data/', strcat(region, '.train.csv')));
    if size(data,1) > 0
        Xd = process_data(data(:,2:end-1));
        yd = data(:,end);
        
        % Generate 10-fold cross validation indices
        [train] = crossvalind('Kfold', size(yd,1), 10);
        
        errsum = 0;
        for i = 1:10
            
            Xtest = Xd(train == i,:);
            ytest = yd(train == i);
            X = Xd(train ~= i,:);
            y = yd(train ~= i);
            
            % Run logistic regression for each of the 10 'categories' (ranks)
            options = optimset('GradObj', 'on', 'MaxIter', 100);
            T = zeros(size(X,2),1);
            it = zeros(size(X,2),1);
            [T,J] = fminunc(@(t)logreg_l2(X, y==i, t, 0.1),it,options);
            
            % Calculate errors
            errsum = errsum + sum(abs((ytest == i)' - (sigmoid(-T' * Xtest')))) / size(ytest,1);
            
        end
        score = 1 - (errsum / 10);
    end
	
end

